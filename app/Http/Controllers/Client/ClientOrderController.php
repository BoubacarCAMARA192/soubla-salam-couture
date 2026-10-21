<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Review;
use App\Models\User;
use App\Notifications\ShopNotification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientOrderController extends Controller
{
    public function __construct() {}

    /** Formulaire de commande/devis (CLI-03), éventuellement pré-rempli depuis un modèle (CAT-05) */
    public function create(Request $request)
    {
        $modelSlug = $request->query('modele');
        $model = $modelSlug ? CatalogModel::where('slug', $modelSlug)->first() : null;
        $mode = $request->query('mode', 'order');

        return view('client.order_create', [
            'model' => $model,
            'mode' => $mode === 'quote' ? 'quote' : 'order',
        ]);
    }

    public function store(Request $request)
    {
        $client = Client::where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->validate([
            'catalog_model_id' => ['nullable', 'exists:catalog_models,id'],
            'type' => ['required', 'in:order,quote'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'fabric' => ['nullable', 'string', 'max:100'],
            'event_date' => ['nullable', 'date', 'after:tomorrow'],
            'inspiration_photo' => ['nullable', 'image', 'max:3072'],
        ], [
            'description.min' => 'Décrivez un peu plus votre demande pour que l\'atelier puisse préparer le devis (au moins 10 caractères).',
            'inspiration_photo.image' => 'Le fichier choisi doit être une image (JPG, PNG, WebP).',
            'inspiration_photo.max' => 'La photo d\'inspiration ne doit pas dépasser 3 Mo.',
        ]);

        $photoPath = null;
        if ($request->hasFile('inspiration_photo')) {
            $photoPath = $request->file('inspiration_photo')->store('inspirations', 'public');
        }

        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'catalog_model_id' => $data['catalog_model_id'] ?? null,
            'type' => $data['type'],
            'description' => $data['description'],
            'fabric' => $data['fabric'] ?? null,
            'event_date' => $data['event_date'] ?? null,
            'inspiration_photo' => $photoPath,
            'status' => Order::REQUESTED,
            'amount' => 0,
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => Order::REQUESTED,
            'changed_by' => $request->user()->id,
            'comment' => $data['type'] === 'quote'
                ? 'Demande de devis envoyée par le client.'
                : 'Demande de commande envoyée par le client.',
        ]);

        // Notification au client (accusé de réception) et à l'équipe de l'atelier
        $this->notifyStaff($order, $data);
        $order->client->user?->notify(new ShopNotification(
            title: 'Votre demande '.$order->reference.' a bien été reçue',
            body: 'L\'atelier Soubla Salam Couture vous répondra très vite pour confirmer '.($order->type === 'quote' ? 'votre demande de devis' : 'votre commande').' et fixer un rendez-vous de mesures.',
            url: route('client.orders.show', $order),
            icon: 'check-circle',
            mail: false
        ));

        $label = $data['type'] === 'quote' ? 'Demande de devis' : 'Commande';

        return redirect()
            ->route('client.orders.show', $order)
            ->with('success', $label." envoyée ! L'atelier la traitera dans la journée. Conservez ce suivi : ".$order->reference);
    }

    public function index(Request $request)
    {
        $client = Client::where('user_id', $request->user()->id)->firstOrFail();
        $status = $request->query('status');

        $query = $client->orders()->with(['catalogModel.photos', 'payments']);
        if ($status && isset(Order::STATUSES[$status])) {
            $query->where('status', $status);
        }

        return view('client.orders', [
            'orders' => $query->orderBy('created_at', 'desc')->paginate(10),
            'statusFilter' => $status,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        $order->load(['catalogModel.photos', 'payments', 'statusHistory.changedBy', 'appointments']);

        return view('client.order_show', ['order' => $order]);
    }

    public function sendMessage(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $client = Client::where('user_id', $request->user()->id)->firstOrFail();

        $message = $order->messages()->create([
            'client_id' => $client->id,
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        // Alerte à l'atelier : dernière commande de suivi for tailleur dashboards
        return back()->with('success', 'Message envoyé à l\'atelier.');
    }

    public function toggleFavorite(Request $request, CatalogModel $model)
    {
        $existing = Favorite::where('user_id', $request->user()->id)
            ->where('catalog_model_id', $model->id)->first();

        if ($existing) {
            $existing->delete();
        } else {
            Favorite::create(['user_id' => $request->user()->id, 'catalog_model_id' => $model->id]);
        }

        return back();
    }

    /** Évaluation de la prestation (CLI-10) — après livraison */
    public function review(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        abort_if($order->status !== Order::DELIVERED, 422, 'Vous pourrez noter la prestation une fois la tenue livrée.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $request->user()->id,
                'client_id' => $order->client_id,
                'catalog_model_id' => $order->catalog_model_id,
                'author_name' => $request->user()->first_name.' '.substr($request->user()->last_name, 0, 1).'.',
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Merci pour votre évaluation ! Elle sera publiée après validation.');
    }

    /** Fiche PDF consultable par le client (reçu / récapitulatif de commande) */
    public function clientPdf(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        $order->load(['client', 'catalogModel', 'payments']);
        $pdf = Pdf::loadView('pdf.client_order_sheet', ['order' => $order]);

        return $pdf->download('commande-'.$order->reference.'.pdf');
    }

    protected function authorizeOrder(Request $request, Order $order): void
    {
        $client = Client::where('user_id', $request->user()->id)->first();

        if (!$client || $order->client_id !== $client->id) {
            abort(403, 'Cette commande ne vous appartient pas.');
        }
    }

    public function notifyStaff(Order $order, array $data = []): void
    {
        $staff = \App\Models\User::whereIn('role', ['tailleur', 'admin'])->where('status', 'active')->get();
        $label = $order->type === 'quote' ? 'Demande de devis' : 'Nouvelle commande';
        foreach ($staff as $employee) {
            $employee->notify(new ShopNotification(
                title: $label.' '.$order->reference.' de '.$order->client->full_name,
                body: 'Le client '.$order->client->full_name.' vient de soumettre un(e) demande/soumission : « '
                    .\Illuminate\Support\Str::limit($order->description, 80).' ». Ouvrez l\'atelier pour valider, chiffrer et planifier.',
                url: route('tailleur.orders.show', $order),
                icon: 'gift',
                mail: false
            ));
        }
    }
}
