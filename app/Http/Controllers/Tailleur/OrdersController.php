<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Fabric;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Notifications\ShopNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = trim($request->query('q', ''));

        $query = Order::with(['client', 'catalogModel', 'tailleur'])
            ->when($status && isset(Order::STATUSES[$status]), fn ($qq) => $qq->where('status', $status))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('reference', 'like', "%{$q}%")
                        ->orWhereHas('client', fn ($c) => $c->where('full_name', 'like', "%{$q}%"));
                });
            })
            ->when(!$request->user()->isAdmin(), fn ($qq) => $qq->where('tailleur_id', $request->user()->id));

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('tailleur.orders.index', [
            'orders' => $orders,
            'statusFilter' => $status,
            'q' => $q,
            'statusTotals' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
        ]);
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('full_name')->get(['id', 'full_name', 'phone']);

        // Pré-remplissage depuis une demande client (modèle ou non)
        $order = null;
        if ($ref = $request->query('from_reference')) {
            $order = Order::with('client', 'catalogModel')->where('reference', $ref)->first();
        }

        return view('tailleur.orders.create', [
            'clients' => $clients,
            'models' => [],
            'order' => $order,
            'fabrics' => Fabric::orderBy('name')->get(),
            'clothingTypes' => \App\Models\ClothingType::activeNames(),
            'latestMeasurements' => collect(),
        ]);
    }

    /** Création d'une commande (client de passage ou à partir d'une demande — TAI-04) */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        $client = Client::findOrFail($data['client_id']);

        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'tailleur_id' => $data['tailleur_id'] ?? $request->user()->id,
            'catalog_model_id' => $data['catalog_model_id'] ?? null,
            'type' => $data['type'],
            'description' => $data['description'],
            'fabric' => $data['fabric'] ?? null,
            'event_date' => $data['event_date'] ?? null,
            'delivery_date' => $data['delivery_date'] ?? null,
            'status' => $data['type'] === 'quote' ? Order::REQUESTED : ($data['amount'] > 0 ? Order::VALIDATED : Order::REQUESTED),
            'amount' => $data['amount'] ?? 0,
            'tailleur_notes' => $data['tailleur_notes'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'comment' => 'Commande créée par l\'atelier.',
            'changed_by' => $request->user()->id,
        ]);

        \App\Models\ActivityLog::record('order.create', 'order', $order->id, ['reference' => $order->reference]);

        // Accusé de réception au client
        $order->client->user?->notify(new ShopNotification(
            title: 'Votre '.$order->type_label.' '.$order->reference.' a été créée',
            body: 'L\'atelier a enregistré '.$order->type_label.' pour vous. Suivez son avancement depuis votre espace.',
            url: route('client.orders.show', $order),
            icon: 'check-circle'
        ));

        return redirect()->route('tailleur.orders.show', $order)->with('success', 'Commande '.$order->reference.' créée.');
    }

    public function show(Order $order)
    {
        $order->load(['client.user', 'catalogModel.photos', 'statusHistory.changedBy', 'payments', 'appointments', 'tailleur']);

        $tailleurs = User::whereIn('role', ['tailleur', 'admin'])->where('status', 'active')->get();

        return view('tailleur.orders.show', [
            'order' => $order,
            'tailleurs' => $tailleurs,
            'cancelled' => $order->status === Order::CANCELLED,
            'canDeliver' => true,
            'payments' => $order->payments()->orderBy('paid_at', 'desc')->get(),
        ]);
    }

    /** Informations du devis, montant, dates, notes atelier et affectation */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'delivery_date' => ['nullable', 'date'],
            'event_date' => ['nullable', 'date'],
            'desc' => ['nullable', 'string', 'max:2000'],
            'tailleur_id' => ['nullable', 'exists:users,id'],
            'tailleur_notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'amount.required' => 'Indiquez le montant total de la commande.',
        ]);

        $order->update([
            'amount' => $data['amount'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'event_date' => $data['event_date'] ?? null,
            'description' => $data['desc'] ?? $order->description,
            'tailleur_notes' => $data['tailleur_notes'] ?? $order->tailleur_notes,
            'tailleur_id' => $data['tailleur_id'] ?? $order->tailleur_id,
        ]);

        \App\Models\ActivityLog::record('order.update', 'order', $order->id);

        return redirect()->route('tailleur.orders.show', $order)->with('success', 'Commande mise à jour.');
    }

    /** Avancement de la commande : statut, photo de progression et justificatif d'annulation (TAI-04) */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'comment' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ], [
            'status.in' => 'Choisissez un statut valide.',
            'photo.image' => 'La photo de progression doit être une image (JPG, PNG, WebP).',
            'photo.max' => 'La photo doit peser moins de 4 Mo.',
        ]);

        // Règles métier
        if ($order->status === Order::CANCELLED) {
            return back()->with('error', 'Cette commande a été annulée : impossible de la faire avancer.');
        }

        if ($data['status'] === Order::CANCELLED && !$request->user()->isAdmin()) {
            return back()->with('error', 'Seul un administrateur peut annuler une commande (avec justification).');
        }

        if ($data['status'] === Order::DELIVERED && $order->remaining_amount > 0) {
            return back()->with('error', 'Encaissez d\'abord le solde réglé avant de marquer la commande comme livrée.');
        }

        $previous = $order->status;
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('orders/progress', 'public');
        }

        $order->update(['status' => $data['status']]);
        $order->statusHistory()->create([
            'status' => $data['status'],
            'comment' => $data['comment'] ?? null,
            'photo' => $photoPath,
            'changed_by' => $request->user()->id,
        ]);

        \App\Models\ActivityLog::record('order.status', 'order', $order->id, [
            'reference' => $order->reference,
            'de' => $previous,
            'vers' => $data['status'],
        ]);

        // Notification au client conformément au tableau 3.6
        $label = Order::statusLabel($data['status']);
        $isNotifiable = in_array($data['status'], array_keys(Order::NOTIFICATION_MESSAGES), true);
        if ($isNotifiable && $order->client->user) {
            $order->client->user->notify(new ShopNotification(
                title: 'Commande '.$order->reference.' : '.mb_strtolower($label),
                body: Order::NOTIFICATION_MESSAGES[$data['status']],
                url: route('client.orders.show', $order),
                icon: 'basket',
            ));
        }

        return redirect()->route('tailleur.orders.show', $order->fresh())
            ->with('success', "Commande mise à « {$label} »" . (($data['status'] !== Order::CANCELLED) ? '' : '.').($data['comment'] ? ' ('.$data['comment'].')' : ''));
    }

    /** Messagerie de l'atelier depuis la fiche commande (TAI-09) */
    public function sendMessage(Request $request, Order $order)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $order->messages()->create([
            'client_id' => $order->client_id,
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        \App\Models\Message::whereNull('read_at')
            ->where('client_id', $order->client_id)
            ->update(['read_at' => now()]);

        \App\Models\ActivityLog::record('message.send', 'order', $order->id);

        return back()->with('success', 'Message envoyé au client.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'type' => ['required', 'in:order,quote'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'catalog_model_id' => ['nullable', 'exists:catalog_models,id'],
            'fabric' => ['nullable', 'string', 'max:100'],
            'event_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'tailleur_id' => ['nullable', 'exists:users,id'],
            'tailleur_notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'description.min' => 'Décrivez la tenue demandée (minimum 10 caractères).',
        ]);
    }
}
