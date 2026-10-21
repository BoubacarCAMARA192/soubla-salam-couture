@extends('layouts.public')

@section('title', 'Accueil')
@section('meta_description', 'Atelier de couture à Dakar : tenues traditionnelles et modernes sur mesure pour la Korité, la Tabaski, les mariages et vos cérémonies.')

@section('content')
<div class="container page-wrap py-4">

    {{-- Bannière + accès rapides (CAT-01) --}}
    <section class="ssc-hero shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge-occasion mb-2 d-inline-block"><i class="bi bi-geo-alt"></i> Dakar — <i class="bi bi-scissors"></i> couture à la main</span>
                <h1 class="mb-3 fw-bold">{{ \App\Models\Setting::get('hero_title') }}</h1>
                <p class="lead mb-4">{{ \App\Models\Setting::get('hero_subtitle') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('catalog.index') }}" class="btn btn-or btn-lg rounded-pill">
                        <i class="bi bi-grid"></i> {{ \App\Models\Setting::get('hero_cta') }}
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill">
                            <i class="bi bi-person-plus"></i> Créer un compte client
                        </a>
                    @endguest
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('atelier_whatsapp')) }}" target="_blank" rel="noopener"
                       class="btn btn-outline-light btn-lg rounded-pill">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <i class="bi bi-rulers display-1 text-or opacity-75"></i>
                <p class="font-decor mt-3 mb-0 opacity-75" style="letter-spacing: 2px;">
                    « Mesures à Dakar, tenues livrées partout »
                </p>
            </div>
        </div>
    </section>

    {{-- Accès rapides --}}
    <div class="row g-3 mt-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('catalog.index', ['category' => 'femme']) }}" class="text-decoration-none">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-person-standing-dress fs-3 text-bordeaux"></i>
                    <p class="mb-0 fw-semibold">Tenues femme</p>
                    <span class="hint">Boubous, caftans, robes</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('catalog.index', ['category' => 'homme']) }}" class="text-decoration-none">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-person-standing fs-3 text-bordeaux"></i>
                    <p class="mb-0 fw-semibold">Tenues homme</p>
                    <span class="hint">Boubous, chemises, kaftans</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('catalog.index', ['occasion' => 'mariage']) }}" class="text-decoration-none">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-suit-heart fs-3 text-bordeaux"></i>
                    <p class="mb-0 fw-semibold">Mariages</p>
                    <span class="hint">Tenues de cérémonie</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('catalog.index', ['type' => 'moderne']) }}" class="text-decoration-none">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-stars fs-3 text-bordeaux"></i>
                    <p class="mb-0 fw-semibold">Style moderne</p>
                    <span class="hint">Coupes tendance</span>
                </div>
            </a>
        </div>
    </div>

    @if ($featured->count())
        <div class="section-title">
            <h2><i class="bi bi-star-fill text-or"></i> Mises en avant</h2>
        </div>
        <div class="row g-3 g-md-4">
            @foreach ($featured as $model)
                @include('partials.model_card', ['m' => $model])
            @endforeach
        </div>
    @endif

    @if ($mostWanted->count())
        <div class="section-title">
            <h2><i class="bi bi-fire text-or"></i> Les plus demandés</h2>
        </div>
        <div class="row g-3 g-md-4">
            @foreach ($mostWanted->take(4) as $model)
                @include('partials.model_card', ['m' => $model])
            @endforeach
        </div>
    @endif

    @if ($holidayPicks->count())
        <div class="section-title">
            <h2><i class="bi bi-moon-stars-fill text-or"></i> Collection des fêtes — Korité & Tabaski</h2>
        </div>
        <div class="row g-3 g-md-4">
            @foreach ($holidayPicks->take(4) as $model)
                @include('partials.model_card', ['m' => $model])
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('catalog.index', ['sort' => 'popular']) }}" class="btn btn-outline-bordeaux rounded-pill">
                Voir tout le catalogue <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    @endif

    {{-- Présentation de l'atelier (CAT-09) --}}
    <div class="section-title">
        <h2><i class="bi bi-house-heart text-or"></i> Notre atelier</h2>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="stat-card h-100">
                <h3 class="font-decor text-bordeaux fs-5">Un savoir-faire familial</h3>
                <p style="white-space: pre-line;">{{ \Illuminate\Support\Str::limit(\App\Models\Setting::get('about_text'), 420) }}</p>
                <a href="{{ route('about') }}" class="btn btn-sm btn-outline-bordeaux rounded-pill">Découvrir l'atelier <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stat-card h-100">
                <h3 class="font-decor text-bordeaux fs-5">Infos pratiques</h3>
                <ul class="list-unstyled mb-0">
                    <li class="spec-line mb-2"><i class="bi bi-geo-alt"></i> {{ \App\Models\Setting::get('atelier_address') }}</li>
                    <li class="spec-line mb-2"><i class="bi bi-telephone"></i> {{ \App\Models\Setting::get('atelier_phone') }}</li>
                    <li class="spec-line mb-2"><i class="bi bi-envelope"></i> {{ \App\Models\Setting::get('atelier_email') }}</li>
                    <li class="spec-line"><i class="bi bi-clock"></i>
                        <span style="white-space: pre-line;">{{ \App\Models\Setting::get('atelier_hours') }}</span>
                    </li>
                </ul>
                <div class="ratio ratio-16x9 mt-3 rounded-3 overflow-hidden">
                    <iframe src="https://maps.google.com/maps?q=Sacre-Coeur-3%20Dakar&output=embed" loading="lazy" title="Carte de l'atelier"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Témoignages clients (CAT-10) --}}
    @if ($reviews->count())
        <div class="section-title">
            <h2><i class="bi bi-chat-quote-fill text-or"></i> Ce que disent nos clients</h2>
        </div>
        <div class="row g-3">
            @foreach ($reviews->take(3) as $review)
                <div class="col-md-4">
                    <div class="stat-card h-100">
                        <span class="rating-stars d-block mb-2">
                            @for ($star = 1; $star <= 5; $star++)
                                <i class="bi bi-star{{ $review->rating >= $star ? '-fill' : '' }}"></i>
                            @endfor
                        </span>
                        <p class="font-decor mb-2">« {{ \Illuminate\Support\Str::limit($review->comment ?? 'Prestation impeccable !', 140) }} »</p>
                        <span class="small text-muted"><strong>{{ $review->author_name }}</strong> @if($review->catalogModel) — tenue « {{ $review->catalogModel->title }} » @endif</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="wash-note mt-5 d-flex flex-wrap align-items-center gap-3">
        <i class="bi bi-shift fs-4 text-bordeaux"></i>
        <div class="flex-grow-1">
            <strong>Commandez en 3 clics :</strong> choisissez un modèle → demandez un devis → l'atelier confirme un rendez-vous de mesures.
            Vos mensurations sont stockées en sécurité et vous recevez un reçu pour chaque paiement.
        </div>
        @guest
            <a href="{{ route('register') }}" class="btn btn-bordeaux rounded-pill">Créer mon compte</a>
        @endguest
    </div>

</div>
@endsection
