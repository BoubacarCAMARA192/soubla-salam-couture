@php
    $user = auth()->user();
    $unreadNotifications = $user?->unreadNotificationsCount() ?? 0;
    $unreadMessages = \App\Models\Message::query()
        ->whereNull('read_at')
        ->whereHas('sender', fn ($q) => $q->where('role', 'client'))
        ->count();
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') – {{ \App\Models\Setting::get('atelier_name') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="app-shell">

    {{-- Menu latéral : mobile => repliable, ordinateur => fixe (§8) --}}
    <aside class="app-sidebar d-lg-block" id="appSidebar">
        <div class="text-center py-3">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <i class="bi bi-scissors fs-2 text-or d-block"></i>
                <span class="brand" style="color: #fff;">{{ \App\Models\Setting::get('atelier_name') }}</span>
                <small>@if($user->isAdmin()) Administration @elseif($user->isTailleur()) Espace atelier @else Mon espace @endif</small>
            </a>
        </div>

        <div class="d-lg-none border-top border-bottom border-light border-opacity-10 py-2 mb-2">
            <button class="btn btn-sm btn-outline-light w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarNav" aria-expanded="false">
                <i class="bi bi-list"></i> Menu
            </button>
        </div>

        <nav class="collapse d-lg-block" id="sidebarNav">
            <div class="nav flex-column">

                @if($user->isClient())
                    <div class="nav-label">Espace client</div>
                    <div><a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}"><i class="bi bi-speedometer2"></i> Mon espace</a></div>
                    <div><a class="nav-link {{ request()->routeIs('client.orders.*') ? 'active' : '' }}" href="{{ route('client.orders.index') }}"><i class="bi bi-bag-check"></i> Mes commandes</a></div>
                    <div><a class="nav-link {{ request()->routeIs('client.appointments') ? 'active' : '' }}" href="{{ route('client.appointments') }}"><i class="bi bi-calendar-week"></i> Mes rendez-vous</a></div>
                    <div><a class="nav-link {{ request()->routeIs('client.favorites') ? 'active' : '' }}" href="{{ route('client.favorites') }}"><i class="bi bi-heart"></i> Mes favoris</a></div>
                    <div><a class="nav-link {{ request()->routeIs('client.profile') ? 'active' : '' }}" href="{{ route('client.profile') }}"><i class="bi bi-rulers"></i> Mes mesures & profil</a></div>
                    <div class="nav-label">Catalogue</div>
                    <div><a class="nav-link" href="{{ route('catalog.index') }}"><i class="bi bi-grid"></i> Parcourir le catalogue</a></div>
                    <div><a class="nav-link" href="{{ route('client.orders.create') }}"><i class="bi bi-plus-square"></i> Demande sur mesure</a></div>

                @elseif($user->isAdmin())
                    <div class="nav-label">Supervision</div>
                    <div><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.stats') ? 'active' : '' }}" href="{{ route('admin.stats') }}"><i class="bi bi-graph-up"></i> Statistiques</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-bag-check"></i> Commandes</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.clients.*') ? 'active' : '' }}" href="{{ route('tailleur.clients.index') }}"><i class="bi bi-people"></i> Clients</a></div>
                    <div class="nav-label">Catalogue & portail</div>
                    <div><a class="nav-link {{ request()->routeIs('admin.catalog.*') ? 'active' : '' }}" href="{{ route('admin.catalog.index') }}"><i class="bi bi-collection"></i> Modèles & modération</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}"><i class="bi bi-star-half"></i> Avis @if(\App\Models\Review::where('status', 'pending')->count())<span class="badge bg-or text-dark ms-1">{{ \App\Models\Review::where('status', 'pending')->count() }}</span>@endif</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.portal') ? 'active' : '' }}" href="{{ route('admin.portal') }}"><i class="bi bi-window"></i> Contenu du portail</a></div>
                    <div class="nav-label">Administration</div>
                    <div><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-person-gear"></i> Comptes & rôles</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Paramètres</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}" href="{{ route('admin.logs') }}"><i class="bi bi-journal-text"></i> Journal d'activité</a></div>
                    <div><a class="nav-link {{ request()->routeIs('admin.export') ? 'active' : '' }}" href="{{ route('admin.export') }}"><i class="bi bi-download"></i> Sauvegarde & exports</a></div>
                    <div class="nav-label">Espace atelier</div>
                    <div><a class="nav-link" href="{{ route('tailleur.dashboard') }}"><i class="bi bi-arrow-left-right"></i> Ouvrir l'atelier</a></div>

                @else
                    <div class="nav-label">Atelier</div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.dashboard') ? 'active' : '' }}" href="{{ route('tailleur.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.clients.*') ? 'active' : '' }}" href="{{ route('tailleur.clients.index') }}"><i class="bi bi-people"></i> Clients</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.orders.*') ? 'active' : '' }}" href="{{ route('tailleur.orders.index') }}"><i class="bi bi-bag-check"></i> Commandes @if(\App\Models\Order::where('status', \App\Models\Order::REQUESTED)->count())<span class="badge bg-or text-dark ms-1">{{ \App\Models\Order::where('status', \App\Models\Order::REQUESTED)->count() }}</span>@endif</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.planning') ? 'active' : '' }}" href="{{ route('tailleur.planning') }}"><i class="bi bi-calendar-week"></i> Planning</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.fabrics.*') ? 'active' : '' }}" href="{{ route('tailleur.fabrics.index') }}"><i class="bi bi-rulers"></i> Stock tissus @if(\App\Models\Fabric::whereColumn('quantity', '<=', 'alert_threshold')->count())<span class="badge bg-danger ms-1">!</span>@endif</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.expenses.*') ? 'active' : '' }}" href="{{ route('tailleur.expenses.index') }}"><i class="bi bi-cash-coin"></i> Dépenses</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.models.*') ? 'active' : '' }}" href="{{ route('tailleur.models.index') }}"><i class="bi bi-collection"></i> Mes modèles</a></div>
                    <div class="nav-label">Relation client</div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.messages.*') ? 'active' : '' }}" href="{{ route('tailleur.messages.index') }}"><i class="bi bi-chat-dots"></i> Messagerie @if($unreadMessages)<span class="badge bg-danger ms-1">{{ $unreadMessages }}</span>@endif</a></div>
                    <div><a class="nav-link {{ request()->routeIs('tailleur.search') ? 'active' : '' }}" href="{{ route('tailleur.search') }}"><i class="bi bi-search"></i> Recherche</a></div>
                @endif

                <div class="nav-label">Mon compte</div>
                <div><a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}"><i class="bi bi-bell"></i> Notifications @if($unreadNotifications)<span class="badge bg-danger ms-1">{{ $unreadNotifications }}</span>@endif</a></div>
                <div><a class="nav-link" href="{{ route('home') }}"><i class="bi bi-globe2"></i> Voir le site public</a></div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-danger bg-transparent border-0 w-100 d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-left"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </aside>

    <div class="app-content">

        <header class="app-topbar d-flex align-items-center flex-wrap gap-2">
            <div class="d-lg-none me-2">
                <button class="btn btn-bordeaux p-1" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarNav">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <h1 class="h5 font-decor mb-0 flex-grow-1 text-bordeaux">@yield('page_title')</h1>

            @if(in_array($user->role, ['tailleur', 'admin']))
                <form action="{{ route('tailleur.search') }}" class="d-none d-md-flex input-group rounded-pill overflow-hidden bg-creme" style="max-width: 280px;">
                    <input type="search" name="q" class="form-control border-0 bg-transparent" placeholder="Rechercher client, commande…" value="">
                    <button class="btn btn-bordeaux rounded-end" type="submit"><i class="bi bi-search"></i></button>
                </form>
            @endif

            <a href="{{ route('notifications.index') }}" class="btn position-relative" title="Notifications">
                <i class="bi bi-bell fs-5"></i>
                @if($unreadNotifications)
                    <span class="badge rounded-pill bg-danger notifications-badge">{{ $unreadNotifications }}</span>
                @endif
            </a>

            <div class="dropdown">
                <button class="btn p-1 d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar-circle">{{ mb_substr($user->first_name, 0, 1).''.mb_substr($user->last_name, 0, 1) }}</span>
                    <span class="d-none d-lg-inline small lh-sm text-start">
                        <strong class="d-block">{{ $user->full_name }}</strong>
                        <span class="text-muted">{{ ['client' => 'Client', 'tailleur' => 'Tailleur', 'admin' => 'Administrateur'][$user->role] }}</span>
                    </span>
                    <i class="bi bi-chevron-down d-none d-lg-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small"><strong>{{ $user->full_name }}</strong><br><em>{{ $user->email }}</em></span></li>
                    @if($user->isClient())
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('client.profile') }}">Mon profil</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-left"></i> Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="p-3 p-md-4 page-wrap" style="max-width: 1180px; margin-inline: auto; width: 100%;">
            @include('partials.flash')

            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}" defer></script>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
