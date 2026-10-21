<?php

namespace App\Http\Controllers;

use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = CatalogModel::published()
            ->with(['photos', 'publishedReviews']);

        // Recherche livre (CAT-03)
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtres
        $query->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('occasion'), fn ($q) => $q->where('occasion', $request->input('occasion')))
            ->when($request->filled('fabric'), fn ($q) => $q->where('suggested_fabric', 'like', '%'.$request->input('fabric').'%'))
            ->when($request->filled('min_price'), fn ($q) => $q->where('indicative_price', '>=', (int) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($q) => $q->where('indicative_price', '<=', (int) $request->input('max_price')));

        // Tri (CAT-03) : nouveautés ou popularité
        $sort = $request->input('sort', 'recent');
        $query->orderByDesc(match ($sort) {
            'popular' => 'orders_count',
            'price_asc' => 'indicative_price',
            'price_desc' => 'indicative_price',
            default => 'created_at',
        });

        if (in_array($sort, ['price_asc', 'price_desc'], true)) {
            $query->orderByDesc('created_at');
        }

        $models = $query->paginate(12);

        // Section mises en avant (CAT-08)
        $newArrivals = CatalogModel::published()->orderByDesc('created_at')->take(8)->get();
        $holidayPicks = CatalogModel::published()
            ->whereIn('occasion', ['Korité', 'Tabaski'])
            ->orderByDesc('created_at')->take(8)->get();
        $mostWanted = CatalogModel::published()->orderByDesc('orders_count')->take(8)->get();

        // Favoris du client connecté (CAT-07)
        $favoriteIds = [];
        if ($request->user()?->isClient()) {
            $favoriteIds = Favorite::where('user_id', $request->user()->id)
                ->pluck('catalog_model_id')->all();
        }

        return view('pages.catalog', [
            'models' => $models,
            'favoriteIds' => $favoriteIds,
            'newArrivals' => $newArrivals,
            'holidayPicks' => $holidayPicks,
            'mostWanted' => $mostWanted,
            'filters' => $request->only(['q', 'category', 'type', 'occasion', 'fabric', 'min_price', 'max_price', 'sort']),
        ]);
    }

    public function show(Request $request, CatalogModel $catalogModel)
    {
        $model = $catalogModel;
        if ($model->status !== 'published') {
            if (!$request->user()?->isTailleur() && !$request->user()?->isAdmin()) {
                abort(404);
            }
        }

        if ($request->user()?->isClient()) {
            $model->increment('views_count');
        }

        $model->load(['photos', 'publishedReviews']);

        $client = $request->user()?->isClient()
            ? Client::where('user_id', $request->user()->id)->first()
            : null;

        $isFavorite = false;
        if ($client) {
            $isFavorite = Favorite::where('user_id', $request->user()->id)
                ->where('catalog_model_id', $model->id)->exists();
        }

        $related = CatalogModel::published()
            ->where('id', '!=', $model->id)
            ->where('category', $model->category)
            ->with('photos')
            ->take(4)->get();

        return view('pages.model', [
            'model' => $model,
            'isFavorite' => $isFavorite,
            'related' => $related,
            'client' => $client,
        ]);
    }
}
