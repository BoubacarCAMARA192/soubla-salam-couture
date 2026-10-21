@php
    $photo = $m->mainPhoto();
    $isFavorite = isset($favoriteIds) && in_array($m->id, $favoriteIds, true);
    $moreCount = $m->photos->count() - 1;
@endphp
<div class="col-6 col-md-4 col-xl-3">
    <div class="card card-ssc h-100">
        <a href="{{ route('catalog.show', $m) }}" class="position-relative d-block text-decoration-none">
            <div class="photo-holder">
                @if ($photo)
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="{{ $m->title }}" loading="lazy">
                @endif
                <span class="price-slot">
                    @if (is_null($m->indicative_price) || $m->indicative_price == 0)
                        Sur devis
                    @else
                        {{ number_format($m->indicative_price, 0, ',', ' ') }} F
                    @endif
                </span>
            </div>
            @if ($m->is_featured)
                <span class="badge bg-or position-absolute top-0 start-0 m-2"><i class="bi bi-star-fill"></i> Coup de cœur</span>
            @elseif ($moreCount)
                <span class="badge position-absolute top-0 start-0 m-2 bg-bordeaux">{{ $moreCount }}+ <i class="bi bi-images"></i></span>
            @endif
        </a>
        <div class="p-3 d-flex flex-column gap-1">
            <span class="small text-muted">
                <i class="bi bi-award me-1"></i>
                {{ \App\Models\CatalogModel::CATEGORY_LABELS[$m->category] }} — {{ $m->type ?? 'Toutes occasions' }}
                @if ($m->occasion)
                    <span class="badge text-bg-light border ms-1">{{ $m->occasion }}</span>
                @endif
            </span>
            <a href="{{ route('catalog.show', $m) }}" class="text-decoration-none fs-6 fw-bold text-bordeaux">{{ $m->title }}</a>

            <div class="d-flex justify-content-between align-items-center mt-1">
                <span class="rating-stars small">
                    @php $rmoy = $m->averageRating(); @endphp
                    @for ($star = 1; $star <= 5; $star++)
                        <i class="bi bi-star{{ $rmoy && round($rmoy) >= $star ? '-fill' : '' }}"></i>
                    @endfor
                    @if ($rmoy) <span class="ms-1 text-muted">{{ $rmoy }}</span> @endif
                </span>
            </div>

            @auth
                @if (auth()->user()->isClient())
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('client.orders.create', ['modele' => $m->slug]) }}" class="btn btn-sm btn-bordeaux flex-fill">
                            <i class="bi bi-bag-plus"></i> Commander
                        </a>
                        <form action="{{ route('client.favorites.toggle', $m) }}" method="POST" class="m-0">
                            @csrf
                            <button class="btn btn-sm {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }}"
                                    title="{{ $isFavorite ? 'Retirer de mes favoris' : 'Ajouter à mes favoris' }}">
                                <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('client.orders.create', ['modele' => $m->slug]) }}"
                       class="btn btn-sm btn-bordeaux flex-fill text-decoration-none">
                        <i class="bi bi-bag-plus"></i> Commander
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-bordeaux" title="Demande de devis (connexion requise)">
                        <i class="bi bi-chat-quote"></i>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
