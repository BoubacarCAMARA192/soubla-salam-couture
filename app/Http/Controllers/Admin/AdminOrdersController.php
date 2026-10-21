<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ShopNotification;
use App\Http\Controllers\Tailleur\OrdersController as TailleurOrders;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $tailleurId = $request->query('tailleur');
        $q = trim($request->query('q', ''));

        $orders = Order::with(['client', 'catalogModel', 'tailleur'])
            ->when($status && isset(Order::STATUSES[$status]), fn ($qq) => $qq->where('status', $status))
            ->when($tailleurId, fn ($qq) => $qq->where('tailleur_id', $tailleurId))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('reference', 'like', "%{$q}%")
                        ->orWhereHas('client', fn ($c) => $c->where('full_name', 'like', "%{$q}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders', [
            'orders' => $orders,
            'statusFilter' => $status,
            'tailleurFilter' => $tailleurId,
            'q' => $q,
            'tailleurs' => User::whereIn('role', ['tailleur', 'admin'])->orderBy('first_name')->get(),
            'statusTotals' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
        ]);
    }

    /** Supervision : réaffectation d'une commande entre tailleurs (ADM-03) */
    public function reassign(Request $request, Order $order)
    {
        $tailleurs = User::whereIn('role', ['tailleur', 'admin'])->get();

        if ($tailleurs->isEmpty()) {
            return back()->with('error', 'Aucun compte de tailleur actif pour la réaffectation.');
        }

        $data = $request->validate(['tailleur_id' => ['required', 'exists:users,id']]);

        $order->update(['tailleur_id' => $data['tailleur_id']]);

        \App\Models\ActivityLog::record('order.reassign', 'order', $order->id, [
            'reference' => $order->reference,
            'tailleur' => User::find($data['tailleur_id'])->full_name,
        ]);

        $tailleur = User::find($data['tailleur_id']);
        $tailleur->notify(new ShopNotification(
            title: 'Commande '.$order->reference.' réaffectée',
            body: 'La commande '.$order->reference.' de '.$order->client->full_name.' vous a été affectée par le gérant.',
            url: route('tailleur.orders.show', $order),
            icon: 'person-check',
            mail: false,
        ));

        return back()->with('success', 'Commande '.$order->reference.' réaffectée à '.$tailleur->full_name.'.');
    }

    public function cancel(Request $request, Order $order)
    {
        $data = $request->validate(['comment' => ['required', 'string', 'max:255']], [
            'comment.required' => 'Justifiez l\'annulation de la commande.',
        ]);

        $previous = $order->status;
        $order->update(['status' => Order::CANCELLED]);
        $order->statusHistory()->create([
            'status' => Order::CANCELLED,
            'comment' => $data['comment'],
            'changed_by' => $request->user()->id,
        ]);

        \App\Models\ActivityLog::record('order.status', 'order', $order->id, [
            'reference' => $order->reference,
            'de' => $previous,
            'vers' => Order::CANCELLED,
        ]);

        $order->client->user?->notify(new ShopNotification(
            title: 'Commande '.$order->reference.' annulée',
            body: 'Votre commande a été annulée par l\'atelier : '.$data['comment'],
            url: route('client.orders.show', $order),
            icon: 'x-circle',
        ));

        return back()->with('success', 'Commande annulée.');
    }

    public function showAdministered(Request $request, Order $order)
    {
        return app(TailleurOrders::class)->show($request, $order);
    }
}
