<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Accueil') – {{ \App\Models\Setting::get('atelier_name') }}</title>

    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('atelier_slogan'))">
    <meta property="og:title" content="@yield('title', 'Accueil') – {{ \App\Models\Setting::get('atelier_name') }}">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('hero_subtitle'))">
    <meta property="og:type" content="website">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="ssc-banner">
    <i class="bi bi-scissors me-1"></i> {{ \App\Models\Setting::get('banner_text') }}
</div>

<nav class="navbar navbar-expand-lg ssc-nav sticky-top">
    <div class="container page-wrap">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="bi bi-scissors fs-2 text-or"></i>
            <span class="lh-sm">
                <strong class="d-block fs-6">{{ strtoupper(\App\Models\Setting::get('atelier_name')) }}</strong>
                <small class="text-muted font-decor fst-italic">{{ \App\Models\Setting::get('atelier_slogan') }}</small>
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav"
                aria-controls="publicNav" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active text-bordeaux' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active text-bordeaux' : '' }}" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid"></i> Catalogue
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active text-bordeaux' : '' }}" href="{{ route('about') }}">
                        <i class="bi bi-info-circle"></i> L'atelier
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active text-bordeaux' : '' }}" href="{{ route('contact') }}">
                        <i class="bi bi-telephone"></i> Contact
                    </a>
                </li>

                @auth
                    @if(auth()->user()->isClient())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.dashboard') }}">
                                <i class="bi bi-person-circle"></i> Mon espace
                            </a>
                        </li>
                    @elseif(auth()->user()->isTailleur())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tailleur.dashboard') }}">
                                <i class="bi bi-shield-lock"></i> Atelier
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-lock"></i> Administration
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-bordeaux rounded-pill ms-lg-2" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Quitter
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-bordeaux rounded-pill" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Créer un compte
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="footer-ssc pt-4 mt-5">
    <div class="container page-wrap pb-3">
        <div class="row g-4">
            <div class="col-md-4">
                <h3 class="text-or font-decor fs-5"><i class="bi bi-scissors"></i> {{ \App\Models\Setting::get('atelier_name') }}</h3>
                <p class="small pe-lg-4">{{ \Illuminate\Support\Str::limit(\App\Models\Setting::get('about_text'), 190) }}</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-or fs-5 fs-6">Nous trouver</h3>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><i class="bi bi-geo-alt me-2 text-or"></i>{{ \App\Models\Setting::get('atelier_address') }}</li>
                    <li class="mb-1"><i class="bi bi-telephone me-2 text-or"></i>{{ \App\Models\Setting::get('atelier_phone') }}</li>
                    <li class="mb-1"><i class="bi bi-envelope me-2 text-or"></i>{{ \App\Models\Setting::get('atelier_email') }}</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h3 class="text-or fs-6">Horaires</h3>
                <p class="small" style="white-space: pre-line;">{{ \App\Models\Setting::get('atelier_hours') }}</p>
                <div class="d-flex gap-3 fs-4">
                    @if(\App\Models\Setting::get('facebook_url'))
                        <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('instagram_url'))
                        <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    @endif
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('atelier_whatsapp')) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <hr class="border-secondary opacity-25">
        <div class="d-flex flex-wrap justify-content-between small">
            <span>{{ \App\Models\Setting::get('atelier_name') }} © {{ now()->year }} — Dakar, Sénégal</span>
            <span>
                <a href="{{ route('privacy') }}" class="me-3"><i class="bi bi-shield-lock"></i> Confidentialité & données personnelles (loi n° 2008-12)</a>
                <a href="{{ route('catalog.index') }}">Catalogue</a>
            </span>
        </div>
    </div>
</footer>

<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}" defer></script>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
