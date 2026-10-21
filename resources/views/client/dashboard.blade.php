@extends('layouts.app')

@section('title', 'Mon espace')
@section('page_title', 'Mon espace — ' . $client->full_name)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <i class="bi bi-bag-check fs-3 text-bordeaux"></i>
            <div class="value">{{ $activeOrders->count() }}</div>
            <div class="hint">commande(s) en cours</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <i class="bi bi-collection fs-3 text-bordeaux"></i>
            <div class="value">{{ $ordersCount }}</div>
            <div class="hint">commande(s) au total</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <i class="bi bi-cash-coin fs-3 text-bordeaux"></i>
            <div class="value">{{ number_format($unpaidTotal, 0, ',', ' ') }}</div>
            <div class="hint">FCFA restant à régler</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <i class="bi bi-calendar-week fs-3 text-bordeaux"></i>
            <div class="value">{{ $upcomingAppointments->count() }}</div>
            <div class="hint">rendez-vous à venir</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="section-title"><h2 class="h5"><i class="bi bi-bag-heart text-or"></i> Mes commandes en cours</h2></div>
        @if ($activeOrders->isEmpty())
            <div class="stat-card text-center py-4">
                <p class="mb-2">Aucune commande en cours.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-bordeaux rounded-pill">
                    <i class="bi bi-grid"></i> Choisir un modèle
                </a>
            </div>
        @else
            @foreach ($activeOrders as $o)
                <div class="stat-card mb-3">
                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                        <div>
                            <a class="fw-bold text-bordeaux fs-6" href="{{ route('client.orders.show', $o) }}">{{ $o->reference }}</a>
                            <span class="badge ms-1" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
                            @if ($o->delivery_date)
                                <div class="small text-muted">Livraison prévue le {{ $o->delivery_date->format('d/m/Y') }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="price">{{ number_format($o->amount, 0, ',', ' ') }} F</div>
                            <div class="small text-muted">payé {{ number_format($o->paid_amount, 0, ',', ' ') }} F</div>
                        </div>
                    </div>
                    <div class="mt-2">
                        @include('partials.timeline', ['order' => $o])
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('client.orders.show', $o) }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-eye"></i> Détails & messagerie</a>
                        <a href="{{ route('client.orders.pdf', $o) }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-file-pdf"></i> Fiche PDF</a>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="section-title"><h2 class="h5"><i class="bi bi-calendar2-check text-or"></i> Prochains rendez-vous</h2></div>
        @if ($upcomingAppointments->isEmpty())
            <div class="stat-card text-center py-3">
                <p class="small mb-2">Aucun rendez-vous programmé.</p>
                <a href="{{ route('client.appointments') }}" class="btn btn-sm btn-outline-bordeaux">Demander un rendez-vous</a>
            </div>
        @else
            @foreach ($upcomingAppointments as $rdv)
                <div class="stat-card d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div>
                        <strong>{{ $rdv->typeLabel() }}</strong>
                        @if ($rdv->order) <span class="badge text-bg-light border ms-1">{{ $rdv->order->reference }}</span> @endif
                    </div>
                    <span class="small text-bordeaux fw-semibold">
                        <i class="bi bi-clock"></i> {{ \App\Helpers\Date::label($rdv->scheduled_at) }}
                    </span>
                </div>
            @endforeach
        @endif
    </div>

    <div class="col-lg-5">
        <div class="section-title"><h2 class="h5"><i class="bi bi-person-heart text-or"></i> Mes informations</h2></div>
        <div class="stat-card mb-3">
            <p class="mb-1"><i class="bi bi-person text-or"></i> {{ $client->full_name }}</p>
            <p class="mb-1"><i class="bi bi-phone text-or"></i> {{ $client->phone ?? '—' }}</p>
            <p class="mb-0"><i class="bi bi-geo-alt text-or"></i> {{ $client->address ?? 'Adresse à renseigner' }}</p>
            <a href="{{ route('client.profile') }}" class="btn btn-sm btn-outline-bordeaux mt-3 rounded-pill">
                <i class="bi bi-rulers"></i> Profil & mensurations
            </a>
        </div>

        <div class="wash-note">
            <i class="bi bi-lightbulb text-or"></i>
            <strong>Astuce :</strong> pensez à prendre rendez-vous <em>avant</em> les fêtes (Korité, Tabaski).
            Vous pouvez recommander sans refaire vos mesures : nos couturiers les retrouvent dans votre fiche.
        </div>
    </div>
</div>
@endsection
