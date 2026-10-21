@extends('layouts.app')

@section('title', 'Administration')
@section('page_title', 'Vue d\'ensemble (ADM-01)')

@section('content')
<div class="row g-3 mb-4">
    @foreach ([
        ['bi-cash-coin', number_format($revenueToday, 0, ',', ' '), 'FCFA encaissés aujourd\'hui'],
        ['bi-calendar-month', number_format($revenueMonth, 0, ',', ' '), 'FCFA encaissés ce mois'],
        ['bi-calendar-year', number_format($revenueYear, 0, ',', ' '), 'FCFA encaissés cette année'],
        ['bi-hourglass-split', $counts['ordersRequested'], 'demandes à traiter'],
        ['bi-scissors', $counts['ordersActive'], 'commandes en atelier'],
        ['bi-exclamation-triangle', $counts['ordersLate'], 'commandes en retard'],
    ] as list($icon, $value, $hint))
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <i class="bi {{ $icon }} fs-3 text-bordeaux"></i>
                <div class="value">{{ $value }}</div>
                <div class="hint">{{ $hint }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <a class="stat-card d-block text-center" href="{{ route('admin.catalog.index') }}">
            <i class="bi bi-collection fs-3 text-or"></i>
            <div class="value">{{ $counts['modelsDraft'] }}</div>
            <div class="hint">modèles en brouillon à modérer</div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a class="stat-card d-block text-center" href="{{ route('admin.reviews.index') }}">
            <i class="bi bi-star fs-3 text-or"></i>
            <div class="value">{{ $counts['reviewsPending'] }}</div>
            <div class="hint">avis à publier</div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a class="stat-card d-block text-center" href="{{ route('tailleur.fabrics.index') }}">
            <i class="bi bi-boxes fs-3 text-or"></i>
            <div class="value">{{ $counts['fabricsLow'] }}</div>
            <div class="hint">tissus sous seuil</div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a class="stat-card d-block text-center" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people fs-3 text-or"></i>
            <div class="value">{{ $counts['clients'] }}</div>
            <div class="hint">fiches clients au total</div>
        </a>
    </div>
</div>

{{-- Graphique encaissements 12 derniers mois (css simple en barres) --}}
<div class="section-title"><h2 class="h5"><i class="bi bi-graph-up text-or"></i> Encaissements des 12 derniers mois</h2></div>
<div class="stat-card mb-4">
    @php $maxVal = max(collect($monthlySeries)->max() ?: 1, 1); $monthCount = 0; @endphp
    <div class="d-flex align-items-end gap-2 flex-wrap" style="height: 190px;">
        @forelse ($monthlySeries as $key => $val)
            @php $date = \Carbon\Carbon::createFromFormat('Y-m', $key); $monthCount++; @endphp
            <div class="text-center" style="width: 58px;">
                <div class="small text-muted">{{ number_format($val/1000, 0, ',', ' ') }} k</div>
                <div class="bg-or rounded-1 mx-auto" style="width: 26px; height: {{ (int) max(4, 140 * $val / $maxVal) }}px;"></div>
                <div class="tiny text-muted mt-1">{{ $date->translatedFormat('M') }}</div>
            </div>
        @empty
            <div class="small text-muted py-4">Aucun encaissement sur les 12 derniers mois.</div>
        @endforelse
    </div>
    @if ($monthCount < 2)
        <div class="small text-muted mt-3"><i class="bi bi-info-circle"></i> Les barres apparaissent dès que des paiements sont enregistrés.</div>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="section-title"><h2 class="h5"><i class="bi bi-person-badge text-or"></i> Charge par couturier</h2></div>
        <div class="stat-card mb-3">
            @forelse ($chargeByTailleur as $t)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $t->full_name }}</span>
                        <strong>{{ $t->active_charge }} en cours</strong>
                    </div>
                    <div class="progress" style="height: 7px;">
                        <div class="progress-bar bg-bordeaux" style="width: {{ (int) max(3, min(100, $t->active_charge * 6)) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="small text-muted mb-0">Aucun compte couturier actif.</p>
            @endforelse
        </div>

        <div class="section-title"><h2 class="h5"><i class="bi bi-trophy text-or"></i> Modèles les plus demandés</h2></div>
        <div class="stat-card">
            @forelse ($topModels as $m)
                <div class="d-flex justify-content-between small py-1 border-bottom">
                    <span>{{ $m->title }}</span>
                    <strong>{{ $m->orders_count }} commande(s)</strong>
                </div>
            @empty
                <p class="small text-muted mb-0">—</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-7">
        <div class="section-title"><h2 class="h5"><i class="bi bi-journal-text text-or"></i> Journal récent</h2></div>
        <div class="stat-card">
            @foreach ($lastLogs as $log)
                <div class="d-flex justify-content-between small py-1 border-bottom flex-wrap gap-1">
                    <span><strong>{{ $log->user?->full_name ?? 'système' }}</strong> — {{ $log->action_label }}</span>
                    <span class="text-muted">{{ $log->created_at->format('d/m H:i') }}</span>
                </div>
            @endforeach
            <a href="{{ route('admin.logs') }}" class="small mt-2 d-inline-block">Tous les événements <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>
@endsection
