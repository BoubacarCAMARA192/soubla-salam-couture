<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogModel;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** Statistiques d'activité (ADM-04) */
class AdminStatsController extends Controller
{
    protected int $days = 30;

    public function index(Request $request)
    {
        $from = Carbon::parse($request->query('from') ?? now()->startOfYear());
        $to = Carbon::parse($request->query('to') ?? now());

        // Précédent intervalle : décalage par rapport à la période
        $prevDuration = $from->diffInDays($to) + 1;

        $ordersByStatus = Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status, count(*) total')
            ->orderBy('created_at')
            ->groupBy('status')->pluck('total', 'status');
        $ordersByStatus = $ordersByStatus->count() > 0 ? collect($ordersByStatus) : collect($ordersByStatus->toArray());

        $paymentsQuery = Payment::query();

        return view('admin.stats', [
            'periodLabel' => \App\Helpers\Date::label($from).' → '.\App\Helpers\Date::label($to),
            'from' => $from,
            'to' => $to,
            'prevDuration' => $prevDuration,
            'ordersByStatus' => $ordersByStatus->count() ? $ordersByStatus : collect($ordersByStatus->toArray()),
            'ordersByPeriod' => $this->ordersByPeriod($from, $to),
            'totalRevenue' => Payment::whereBetween('paid_at', [$from, $to])->sum('amount'),
            'totalCollected' => Payment::query()
                ->whereBetween('paid_at', [$from, $to])->sum('amount'),
            'unpaid' => Order::whereIn('status', Order::ACTIVE_STATUSES)->get()->sum(fn ($o) => $o->remaining_amount),
            'invoiced' => Order::whereBetween('created_at', [$from, $to])->sum('amount'),
            'topModels' => CatalogModel::withCount('orders')->orderByDesc('orders_count')->take(8)->get(),
            'chargePerTailleur' => User::where('role', 'tailleur')->withCount(['ordersAsTailleur as charge' => fn ($q) => $q->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))])->get(),
            'ordersByCategory' => Order::query()
                ->join('catalog_models', 'catalog_models.id', '=', 'orders.catalog_model_id')
                ->whereBetween('orders.created_at', [$from, $to])
                ->selectRaw('catalog_models.category, count(*) total')
                ->groupBy('catalog_models.category')->pluck('total', 'category'),
        ]);
    }

    protected function ordersByPeriod(Carbon $from, Carbon $to): array
    {
        $orders = Order::where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->get(['created_at']);

        $series = [];
        foreach ($orders as $order) {
            $series[$order->created_at->format('Y-m-d')] = ($series[$order->created_at->format('Y-m-d')] ?? 0) + 1;
        }
        ksort($series);

        return $series;
    }
}
