<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\Fabric;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();

        return view('admin.dashboard', [
            'counts' => [
                'ordersRequested' => Order::where('status', Order::REQUESTED)->count(),
                'ordersActive' => Order::whereIn('status', Order::ACTIVE_STATUSES)->count(),
                'ordersLate' => Order::whereIn('status', Order::ACTIVE_STATUSES)
                    ->whereDate('delivery_date', '<', $now)->count(),
                'modelsDraft' => CatalogModel::where('status', 'draft')->count(),
                'modelsPublished' => CatalogModel::published()->count(),
                'modelsFeatured' => CatalogModel::where('is_featured', true)->count(),
                'reviewsPending' => Review::where('status', 'pending')->count(),
                'fabricsLow' => Fabric::whereColumn('quantity', '<=', 'alert_threshold')->count(),
                'clients' => Client::count(),
            ],
            'revenueToday' => Payment::whereDate('paid_at', $now->toDateString())->sum('amount'),
            'revenueMonth' => Payment::whereDate('paid_at', '>=', $startOfMonth->toDateString())->sum('amount'),
            'revenueYear' => Payment::whereYear('paid_at', $now->year)->sum('amount'),
            'monthlySeries' => $this->monthlySeries(),
            'lastLogs' => ActivityLog::with('user')->orderByDesc('created_at')->take(8)->get(),
            'topModels' => CatalogModel::published()->withCount('orders')->orderByDesc('orders_count')->take(5)->get(),
            'chargeByTailleur' => User::where('role', 'tailleur')->withCount(['ordersAsTailleur as active_charge' => fn ($q) => $q->whereIn('status', Order::ACTIVE_STATUSES)])->get(),
        ]);
    }

    protected function monthlySeries(): array
    {
        $payments = Payment::where('paid_at', '>=', now()->subYear())
            ->get(['paid_at', 'amount']);

        $series = [];
        foreach ($payments as $payment) {
            $key = $payment->paid_at->format('Y-m');
            $series[$key] = ($series[$key] ?? 0) + (float) $payment->amount;
        }

        ksort($series);

        return $series;
    }
}
