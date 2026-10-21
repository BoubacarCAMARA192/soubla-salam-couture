@extends('layouts.public')

@section('title', 'Catalogue des modèles')
@section('meta_description', 'Parcourez le catalogue : boubous, caftans, robes et tenues traditionnelles ou modernes de Soubla Salam Couture.')

@section('content')
<div class="container page-wrap py-4">

    {{-- En-tête catalogue --}}
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <h1 class="h2 font-decor text-bordeaux mb-0 me-auto"><i class="bi bi-grid"></i> Catalogue</h1>
        <form class="d-flex gap-2 flex-grow-1 flex-md-grow-0" method="GET" action="{{ route('catalog.index') }}">
            @foreach (['category', 'type', 'occasion', 'fabric', 'min_price', 'max_price', 'sort'] as $pf)
                @if (($filters[$pf] ?? null))
                    <input type="hidden" name="{{ $pf }}" value="{{ $filters[$pf] }}">
                @endif
            @endforeach
            <div class="input-group bg-white rounded-pill overflow-hidden" style="max-width: 350px;">
                <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control border-0" placeholder="Chercher un modèle…">
                <button class="btn btn-bordeaux" type="submit">OK</button>
            </div>
        </form>
        <button class="btn btn-outline-bordeaux rounded-pill d-lg-none" data-bs-toggle="collapse" data-bs-target="#panelFiltres">
            <i class="bi bi-funnel"></i> Filtres
        </button>
    </div>

    {{-- Filtres : panneau repli à l' mobile, colonne latérale sur ordinateur (§8) --}}
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="collapse d-lg-block stat-card" id="panelFiltres">
                <h2 class="h6 font-decor text-bordeaux mb-3"><i class="bi bi-funnel"></i> Filtres</h2>
                <form method="GET" action="{{ route('catalog.index') }}">
                    @if (($filters['q'] ?? null))<input type="hidden" name="q" value="{{ $filters['q'] }}">@endif

                    <label class="form-label small fw-semibold">Catégorie</label>
                    <select name="category" class="form-select mb-3">
                        <option value="">Homme, femme, enfant — tout</option>
                        @foreach (\App\Models\CatalogModel::CATEGORIES as $opt)
                            <option value="{{ $opt }}" @selected(($filters['category'] ?? '') === $opt)>{{ \App\Models\CatalogModel::CATEGORY_LABELS[$opt] }}</option>
                        @endforeach
                    </select>

                    <label class="form-label small fw-semibold">Style</label>
                    <select name="type" class="form-select mb-3">
                        <option value="">Traditionnel ou moderne</option>
                        @foreach (\App\Models\CatalogModel::TYPES as $opt)
                            <option value="{{ $opt }}" @selected(($filters['type'] ?? '') === $opt)>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>

                    <label class="form-label small fw-semibold">Occasion</label>
                    <select name="occasion" class="form-select mb-3">
                        <option value="">Toutes les occasions</option>
                        @foreach (\App\Models\CatalogModel::OCCASIONS as $opt)
                            <option value="{{ $opt }}" @selected(($filters['occasion'] ?? '') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>

                    <label class="form-label small fw-semibold">Tissu contient</label>
                    <input type="text" name="fabric" value="{{ $filters['fabric'] ?? '' }}" class="form-control mb-3" placeholder="bazin, wax, getzner…">

                    <label class="form-label small fw-semibold">Prix indicatif (FCFA)</label>
                    <div class="d-flex gap-2 mb-3">
                        <input type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="form-control" placeholder="min">
                        <input type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="form-control" placeholder="max">
                    </div>

                    <label class="form-label small fw-semibold">Trier par</label>
                    <select name="sort" class="form-select mb-3">
                        <option value="recent" @selected(($filters['sort'] ?? 'recent') === 'recent')>Nouveautés</option>
                        <option value="popular" @selected(($filters['sort'] ?? '') === 'popular')>Popularité (les plus demandés)</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Prix croissant</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Prix décroissant</option>
                    </select>

                    <div class="d-flex gap-2">
                        <button class="btn btn-bordeaux flex-fill"><i class="bi bi-check2"></i> Appliquer</button>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-bordeaux"><i class="bi bi-x-lg"></i></a>
                    </div>
                </form>
            </div>

            <div class="wash-note mt-3">
                <i class="bi bi-info-circle"></i> Aucune inscription nécessaire pour parcourir le catalogue. Compte client requis pour commander.
            </div>
        </aside>

        {{-- Grille responsive : 1-2 colonnes mobile, 3-4 sur ordinateur --}}
        <section class="col-lg-9">
            @if (!$models->count())
                <div class="stat-card text-center py-5">
                    <i class="bi bi-emoji-smile display-5 text-or"></i>
                    <p class="mt-3 mb-1"><strong>Aucun modèle ne correspond à votre recherche.</strong></p>
                    <p class="text-muted small">Essayez d'élargir vos filtres — ou <a href="{{ route('contact') }}">contactez-nous</a> pour une demande sur mesure.</p>
                </div>
            @else
                <p class="text-muted small mb-2">{{ $models->total() }} modèle(s) — page {{ $models->currentPage() }} / {{ $models->lastPage() }}</p>
                <div class="row g-3 g-md-4">
                    @foreach ($models as $model)
                        @include('partials.model_card', ['m' => $model])
                    @endforeach
                </div>
                {{ $models->links() }}
            @endif

            {{-- Sections mises en avant (CAT-08) --}}
            @if (!request()->query() && $newArrivals->count())
                <div class="section-title"><h2 class="h5"><i class="bi bi-stars text-or"></i> Nouveautés de l'atelier</h2></div>
                <div class="row g-3 g-md-4">
                    @foreach ($newArrivals->take(4) as $model)
                        @include('partials.model_card', ['m' => $model])
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
