@extends('layouts.app')

@section('title', 'Atelier — Tableau de bord')
@section('page_title', 'Tableau de bord atelier')

@section('content')
{{-- Alertes contextuelles (TAI-01) --}}
@if ($overload['is_overloaded'] ?? false)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Période à forte demande ({{ $overload['period'] }}) :</strong>
            {{ $overload['message'] ?? 'proposez un délai réaliste aux nouveaux clients et priorisez les événements proches.' }}
        </div>
    </div>
@endif

@if ($lowFabrics->count())
    <div class="alert alert-dark d-flex align-items-center gap-2 flex-wrap">
        <i class="bi bi-pin-fill"></i>
        <span><strong>Stock faible :</strong>
            {{ $lowFabrics->pluck('name')->implode(', ') }}
            <a href="{{ route('tailleur.fabrics.alerts') }}" class="ms-1">voir les alertes <i class="bi bi-arrow-right"></i></a>
        </span>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card"><i class="bi bi-hourglass-split fs-3 text-bordeaux"></i>
            <div class="value">{{ $newOrders->count() }}</div>
            <div class="hint">demandes à traiter</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card"><i class="bi bi-scissors fs-3 text-bordeaux"></i>
            <div class="value">{{ $totalActive }}</div>
            <div class="hint">commandes en atelier</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card"><i class="bi bi-truck fs-3 text-bordeaux"></i>
            <div class="value">{{ $deliveriesToday->count() }}</div>
            <div class="hint">livraisons aujourd'hui</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card"><i class="bi bi-cash-coin fs-3 text-bordeaux"></i>
            <div class="value">{{ $quoteRequests }}</div>
            <div class="hint">devis en attente</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="section-title"><h2 class="h5"><i class="bi bi-calculator text-or"></i> Livraisons du jour ({{ \App\Helpers\Date::label(now()) }})</h2></div>
        @forelse ($deliveriesToday as $o)
            <div class="stat-card d-flex justify-content-between align-items-center py-2 px-3 mb-2 flex-wrap gap-2">
                <span>
                    <a class="text-bordeaux fw-bold" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a>
                    {{ $o->client->full_name }}
                    @if ($o->remaining_amount > 0)
                        <span class="badge bg-danger ms-1">à encaisser {{ number_format($o->remaining_amount, 0, ',', ' ') }} F</span>
                    @endif
                </span>
                <span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Aucune livraison prévue aujourd'hui.</div>
        @endforelse

        <div class="section-title"><h2 class="h5"><i class="bi bi-truck-flatbed text-or"></i> Demain ({{ number_format($deliveriesTomorrow->count()) }})</h2></div>
        @forelse ($deliveriesTomorrow as $o)
            <div class="stat-card d-flex justify-content-between py-2 px-3 mb-2">
                <span class="text-bordeaux fw-bold">{{ $o->reference }} · {{ $o->client->full_name }}</span>
                <span class="small text-muted">{{ number_format($o->remaining_amount, 0, ',', ' ') }} F restant</span>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Rien prévu demain.</div>
        @endforelse
    </div>

    <div class="col-xl-6">
        <div class="section-title"><h2 class="h5"><i class="bi bi-inbox text-or"></i> Demandes entrantes</h2></div>
        @forelse ($newOrders as $o)
            <div class="stat-card py-2 px-3 mb-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>
                        <a class="text-bordeaux fw-bold" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a>
                        <span class="badge {{ $o->type === 'quote' ? 'bg-warning text-dark' : 'bg-or text-dark' }}">{{ $o->type === 'quote' ? 'devis' : 'commande' }}</span>
                    </span>
                    <a href="{{ route('tailleur.orders.show', $o) }}" class="btn btn-sm btn-outline-bordeaux">Examiner</a>
                </div>
                <p class="small text-muted mb-0 mt-1">{{ \Illuminate\Support\Str::limit($o->description, 110) }} — {{ $o->client->full_name }}</p>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Aucune nouvelle demande.</div>
        @endforelse

        <div class="section-title"><h2 class="h5"><i class="bi bi-calendar2-week text-or"></i> Rendez-vous du jour</h2></div>
        @forelse ($appointmentsToday as $rdv)
            <div class="stat-card d-flex justify-content-between py-2 px-3 mb-2">
                <span><strong>{{ $rdv->client->full_name }}</strong> · {{ $rdv->typeLabel() }}</span>
                <span class="small text-bordeaux">{{ $rdv->scheduled_at->format('H\hi') }}</span>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Pas de rendez-vous aujourd'hui.</div>
        @endforelse
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="section-title"><h2 class="h5"><i class="bi bi-kanban text-or"></i> Mes commandes en cours</h2></div>
        @forelse ($myOrders as $o)
            <div class="stat-card mb-2 py-2 px-3">
                <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                    <span>
                        <a class="text-bordeaux fw-bold" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a>
                        <span class="text-muted small">· {{ $o->client->full_name }}</span>
                    </span>
                    <span class="d-flex gap-2 align-items-center">
                        <span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
                        @if ($o->delivery_date)
                            <span class="small {{ $o->is_late ? 'text-danger fw-bold' : 'text-muted' }}">{{ $o->delivery_date->format('d/m') }}</span>
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Aucune commande en cours @if (!auth()->user()->isAdmin())assignée à votre charge @endif.</div>
        @endforelse
    </div>
    <div class="col-lg-4">
        <div class="section-title"><h2 class="h5"><i class="bi bi-arrow-right-short text-or"></i> Prochains RDV</h2></div>
        @forelse ($appointmentsUpcoming as $rdv)
            <div class="stat-card py-2 px-3 mb-2 d-flex justify-content-between">
                <span class="small">{{ $rdv->client->full_name }} · {{ $rdv->typeLabel() }}</span>
                <span class="small text-bordeaux">{{ \App\Helpers\Date::label($rdv->scheduled_at) }}</span>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">—</div>
        @endforelse

        <div class="stat-card text-center py-3 mt-3">
            <i class="bi bi-chat-left-dots fs-4 text-or"></i>
            <div class="value fs-4">{{ $pendingMessages }}</div>
            <div class="hint">messagerie non lue</div>
            <a href="{{ route('tailleur.messages.index') }}" class="btn btn-sm btn-outline-bordeaux mt-2 rounded-pill">Ouvrir la messagerie</a>
        </div>
    </div>
</div>
@endsection
