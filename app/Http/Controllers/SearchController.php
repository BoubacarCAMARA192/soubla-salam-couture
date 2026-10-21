<?php

namespace App\Http\Controllers;

use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/** Recherche globale clients / commandes / modèles (TRA-05) */
class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        $clients = collect();
        $orders = collect();
        $models = collect();

        if ($q !== '') {
            $clients = Client::where(function ($w) use ($q) {
                    $w->where('full_name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%");
                })
                ->withCount('orders')
                ->orderBy('full_name')->get();

            $orders = Order::with('client')
                ->where(function ($w) use ($q) {
                    $w->where('reference', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('fabric', 'like', "%{$q}%");
                })
                ->orderBy('created_at', 'desc')->get();

            $models = CatalogModel::with('photos')
                ->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('suggested_fabric', 'like', "%{$q}%");
                })
                ->orderBy('created_at', 'desc')->get();
        }

        return view('tailleur.search', compact('q', 'clients', 'orders', 'models'));
    }
}
