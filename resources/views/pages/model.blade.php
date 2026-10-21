@extends('layouts.public')

@section('title', $model->title)
@section('meta_description', \Illuminate\Support\Str::limit($model->description ?? 'Modèle du catalogue de la boutique de couture Soubla Salam Couture à Dakar.', 150))
@section('og_image', asset('storage'.($model->mainPhoto()? '/'.$model->mainPhoto()->path : '')))

@section('content')
@php
    $photo = $model->mainPhoto();
    $moy = $model->averageRating();
@endphp
<div class="container page-wrap py-4">

    <nav aria-label="Fil d'arianne">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Catalogue</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['category' => $model->category]) }}">{{ $model->categoryLabelAttribute() }}</a></li>
            <li class="breadcrumb-item active text-bordeaux">{{ $model->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Galerie : pleine largeur sur mobile, à gauche sur ordinateur --}}
        <div class="col-lg-6" data-photo-galerie>
            @if ($photo)
                <img data-photo-main class="detail-photo" src="{{ asset('storage/'.$photo->path) }}" alt="{{ $photo->alt ?? $model->title }}" title="Cliquez pour agrandir">
            @else
                <div class="detail-photo d-grid place-items-center" style="aspect-ratio: 3/4; display: grid; place-items: center;">
                    <i class="bi bi-scissors fs-1 text-or"></i>
                    <span class="text-muted">Photo à venir</span>
                </div>
            @endif

            @if ($model->photos->count() > 1)
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach ($model->photos as $p)
                        <img data-photo-zoom class="thumb-photo {{ $loop->first ? 'active' : '' }}"
                             src="{{ asset('storage/'.$p->path) }}"
                             alt="{{ $p->alt ?? $model->title }} — vue {{ $loop->index + 1 }}" loading="lazy">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Informations et actions : à droite sur ordinateur (§8) --}}
        <div class="col-lg-6">

            <div class="d-flex gap-2 mb-2">
                <span class="badge bg-bordeaux">{{ \App\Models\CatalogModel::CATEGORY_LABELS[$model->category] }}</span>
                @if ($model->type) <span class="badge text-bg-light border">{{ ucfirst($model->type) }}</span> @endif
                @if ($model->occasion) <span class="badge bg-or text-dark"><i class="bi bi-calendar2-heart"></i> {{ $model->occasion }}</span> @endif
            </div>

            <h1 class="h1 font-decor text-bordeaux">{{ $model->title }}</h1>

            <p class="mb-2 price fs-3">
                @if (is_null($model->indicative_price) || $model->indicative_price == 0)
                    Prix : sur devis
                @else
                    ≈ {{ number_format($model->indicative_price, 0, ',', ' ') }} FCFA
                    <span class="fs-6 text-muted fw-normal">prix indicatif</span>
                @endif
            </p>

            @if ($moy)
                <p class="rating-stars mb-3">
                    @for ($star = 1; $star <= 5; $star++)
                        <i class="bi bi-star{{ round($moy) >= $star ? '-fill' : '' }}"></i>
                    @endfor
                    <span class="text-muted small ms-1">{{ $moy }}/5 ({{ $model->publishedReviews->count() }} avis)</span>
                </p>
            @endif

            @if ($model->description)
                <p class="mb-3">{{ $model->description }}</p>
            @endif

            <div class="stat-card p-3 mb-3">
                <span class="spec-line d-block mb-2"><i class="bi bi-hexagon"></i> <span><strong>Tissus conseillés :</strong> {{ $model->suggested_fabric ?? 'à définir avec le tailleur' }}</span></span>
                <span class="spec-line d-block mb-2"><i class="bi bi-palette"></i> <span><strong>Couleurs conseillées :</strong> {{ $model->suggested_colors ?? 'au choix, selon vos envies' }}</span></span>
                <span class="spec-line d-block"><i class="bi bi-hourglass-split"></i> <span><strong>Confection :</strong> {{ $model->delay_days ? $model->delay_days.' jour(s) approximativement' : 'délai à convenir' }}</span></span>
            </div>

            {{-- Boutons Commander / Demander un devis (CAT-05) : connexion ou inscription, puis commande pré-remplie --}}
            <div class="mobile-sticky-cta">
                <div class="row g-2">
                    <div class="col-7 col-lg-5">
                        <a href="{{ route('catalog.show', $model) }}#commander" class="d-none"></a>
                        <a href="{{ route('client.orders.create', ['modele' => $model->slug, 'mode' => 'order']) }}"
                           class="btn btn-bordeaux w-100 rounded-pill">
                            <i class="bi bi-bag-plus"></i> Commander ce modèle
                        </a>
                    </div>
                    <div class="col-5 col-lg-4">
                        <a href="{{ route('client.orders.create', ['modele' => $model->slug, 'mode' => 'quote']) }}"
                           class="btn btn-outline-bordeaux w-100 rounded-pill">
                            <i class="bi bi-chat-quote"></i> Demander un devis
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        @auth
                            @if (auth()->user()->isClient())
                                <form method="POST" action="{{ route('client.favorites.toggle', $model) }}">
                                    @csrf
                                    <button class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} w-100 rounded-pill" title="Favoris">
                                        <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }}"></i> {{ $isFavorite ? 'Fav.' : 'Favori' }}
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Partage WhatsApp / Facebook / copie de lien (CAT-06) --}}
            <div class="d-flex gap-2 flex-wrap mt-3">
                <a class="btn btn-sm btn-outline-bordeaux rounded-pill" data-share-whatsapp target="_blank" rel="noopener">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
                <a class="btn btn-sm btn-outline-bordeaux rounded-pill" data-share-facebook target="_blank" rel="noopener">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
                <button class="btn btn-sm btn-outline-bordeaux rounded-pill" data-copy="{{ url()->current() }}">
                    <i class="bi bi-link-45deg"></i> Copier le lien
                </button>
            </div>

            @if (auth()->check() && (auth()->user()->isTailleur() || auth()->user()->isAdmin()))
                <div class="wash-note mt-3">
                    Aperçu back-office — Statut : <strong>{{ $model->status }}</strong>, vues : {{ $model->views_count }}, commandes : {{ $model->orders_count }}.

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.catalog.edit', $model) }}" class="ms-2">Modifier</a>
                    @else
                        <a href="{{ route('tailleur.models.edit', $model) }}" class="ms-2">Modifier</a>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- Avis publiés --}}
    @if ($model->publishedReviews->count())
        <div class="section-title"><h2 class="h5"><i class="bi bi-chat-quote text-or"></i> Avis clients</h2></div>
        <div class="row g-3">
            @foreach ($model->publishedReviews->take(6) as $review)
                <div class="col-md-4">
                    <div class="stat-card h-100">
                        <span class="rating-stars d-block mb-1">
                            @for ($star = 1; $star <= 5; $star++)
                                <i class="bi bi-star{{ $review->rating >= $star ? '-fill' : '' }}"></i>
                            @endfor
                        </span>
                        <p class="small mb-1">{{ $review->comment ?? 'Superbe prestation !' }}</p>
                        <span class="small text-muted">{{ $review->author_name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modèles proches --}}
    @if ($related->count())
        <div class="section-title"><h2 class="h5"><i class="bi bi-collection text-or"></i> Dans le même esprit</h2></div>
        <div class="row g-3 g-md-4">
            @foreach ($related as $macItem)
                @include('partials.model_card', ['m' => $macItem])
            @endforeach
        </div>
    @endif
</div>
@endsection
