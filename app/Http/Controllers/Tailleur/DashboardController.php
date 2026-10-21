<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Jobs\SendAppointmentReminder;
use App\Models\Appointment;
use App\Models\Fabric;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ShopNotification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();
        $tomorrow = now()->addDay();

        $query = Order::with(['client', 'catalogModel']);

        return view('tailleur.dashboard', [
            // Commandes à traiter (demandées sans tailleur) + en retard
            'newOrders' => (clone $query)->where('status', Order::REQUESTED)->orderBy('created_at')->get(),
            'lateOrders' => (clone $query)->whereIn('status', Order::ACTIVE_STATUSES)
                ->whereDate('delivery_date', '<', now()->toDateString())->orderBy('delivery_date')->get(),
            'myOrders' => $request->user()->isAdmin() ? collect() :
                (clone $query)->where('tailleur_id', $request->user()->id)
                    ->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))->get(),

            // Livraisons du jour et du lendemain (TAI-01)
            'deliveriesToday' => (clone $query)->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))
                ->whereDate('delivery_date', $today)->get(),
            'deliveriesTomorrow' => (clone $query)->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))
                ->whereDate('delivery_date', $tomorrow)->get(),

            // Rendez-vous
            'appointmentsToday' => Appointment::with('client')->today()->orderBy('scheduled_at')->get(),
            'appointmentsUpcoming' => Appointment::with('client')->upcoming()
                ->whereDate('scheduled_at', '>', today())->orderBy('scheduled_at')->take(5)->get(),

            // Statistiques rapides
            'pendingMessages' => \App\Models\Message::whereNull('read_at')
                ->whereHas('sender', fn ($q) => $q->where('role', 'client'))->count(),
            'lowFabrics' => Fabric::whereColumn('quantity', '<=', 'alert_threshold')->get(),
            'totalActive' => Order::whereIn('status', Order::ACTIVE_STATUSES)->count(),
            'quoteRequests' => Order::where('status', Order::REQUESTED)->where('type', 'quote')->count(),

            // Alertes d'en charge Kimbap dynamic
            'overload' => $this->detectOverload(),
        ]);
    }

    /** Alerte de surcharge en période de fêtes (TAI-05) : commandes dues dans les 14 jours */
    protected function detectOverload(): array
    {
        $soon = Order::whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))
            ->whereBetween('delivery_date', [now()->toDateString(), now()->addDays(14)->toDateString()])
            ->count();

        $workforce = User::where('role', 'tailleur')->where('status', 'active')->count();
        $capacityPerTailleur = 10; // capacité hebdo estimée par tailleur (paramétrable)

        $overload = $workforce > 0
            ? $soon > $workforce * $capacityPerTailleur * 2
            : $soon > 20;

        return ['soon' => $soon, 'workforce' => $workforce, 'overload' => $overload];
    }
}
