@extends('layouts.app')

@section('title', 'Statistiques')
@section('page_title', 'Statistiques d\'activité (ADM-04)')

@section('content')
{{-- Sélecteur de période --}}
<div class="stat-card p-3 mb-3">
    <form method="GET" action="{{ route('admin.stats') }}" class="row g-2 align-items-end">
        <div class="col-6 col-md-3">
            <label class="form-label small" for="f1">Du</label>
            <input id="f1" name="from" type="date" class="form-control" value="{{ $from->format('Y-m-d') }}">
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label small" for="t1">Au</label>
            <input id="t1" name="to" type="date" class="form-control" value="{{ $to->format('Y-m-d') }}">
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button class="btn btn-bordeaux flex-grow-1"><i class="bi bi-arrow-clockwise"></i> Recalculer</button>
            <a class="btn btn-outline-bordeaux" href="{{ route('admin.stats') }}" title="Année en cours"><i class="bi bi-calendar3"></i></a>
        </div>
    </form>
    <div class="small text-muted mt-2"><i class="bi bi-calendar-week"></i> Période : {{ $periodLabel }}</div>
</div>

@php
    $maxOrdersPeriod = max(array_values($ordersByPeriod)) ?: 1;
@endphp

<div class="row g-4">
    <div class="col-lg-5">
        <div class="section-title"><h2 class="h6"><i class="bi bi-cash-coin text-or"></i> Chiffres de la période</h2></div>
        <div class="stat-card mb-3">
            <div class="d-flex justify-content-between py-1 border-bottom"><span>Commandes facturées</span><strong>{{ number_format($invoiced, 0, ',', ' ') }} F</strong></div>
            <div class="d-flex justify-content-between py-1 border-bottom text-success"><span>Encaissé</span><span>{{ number_format($totalCollected, 0, ',', ' ') }} F</span></div>
            <div class="d-flex justify-content-between py-1 text-danger"><span>Créances actives (hors période)</span><span>{{ number_format($unpaid, 0, ',', ' ') }} F</span></div>
        </div>

        <div class="section-title"><h2 class="h6"><i class="bi bi-pie-chart text-or"></i> Commandes par statut</h2></div>
        <div class="stat-card mb-3">
            @forelse (\App\Models\Order::STATUSES as $key => $info)
                @php $n = $ordersByStatus[$key] ?? 0; @endphp
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span><span class="dot d-inline-block me-1" style="background-color: {{ $info[1] }}; width: 9px; height: 9px; border-radius: 50%;"></span>{{ $info[0] }}</span>
                        <strong>{{ $n }}</strong>
                    </div>
                </div>
            @empty
                <p class="small text-muted mb-0">—</p>
            @endforelse
            @if ($ordersByStatus->sum() === 0)
                <p class="small text-muted mb-0">Aucune commande sur la période.</p>
            @endif
        </div>

        <div class="section-title"><h2 class="h6"><i class="bi bi-gender-split text-or"></i> Commandes par catégorie</h2></div>
        <div class="stat-card">
            @forelse (['femme' => 'Femme', 'homme' => 'Homme', 'enfant' => 'Enfant'] as $key => $label)
                <div class="d-flex justify-content-between small py-1 border-bottom">
                    <span>{{ $label }}</span><strong>{{ $ordersByCategory[$key] ?? 0 }}</strong>
                </div>
            @empty
                <p class="small text-muted mb-0">—</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-7">
        <div class="section-title"><h2 class="h6"><i class="bi bi-bar-chart text-or"></i> Commandes par jour</h2></div>
        <div class="stat-card mb-4">
            @php $maxCount = max(1, count($ordersByPeriod) ? max($ordersByPeriod) : 1); @endphp
            @if (count($ordersByPeriod) === 0)
                <p class="small text-muted mb-0">Aucune commande sur cette période.</p>
            @else
                <div class="d-flex align-items-end gap-1 flex-wrap" style="height: 140px;">
                    @foreach ($ordersByPeriod as $date => $n)
                        <div class="text-center" style="width: 22px;" title="{{ $date }} : {{ $n }} commande(s)">
                            <div class="bg-bordeaux rounded-1 mx-auto" style="width: 14px; height: {{ (int) max(3, 120 * $n / $maxCount) }}px;"></div>
                        </div>
                    @endforeach
                </div>
                <div class="small text-muted mt-2">{{ count($ordersByPeriod) }} jour(s) actif(s) — au plus {{ $maxCount }} commande(s)/jour</div>
            @endif
        </div>

        <div class="section-title"><h2 class="h6"><i class="bi bi-person-badge text-or"></i> Charge par atelier</h2></div>
        <div class="stat-card mb-4">
            @forelse ($chargePerTailleur as $t)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $t->full_name }}</span>
                        <strong>{{ $t->charge }} commande(s)</strong>
                    </div>
                    <div class="progress" style="height: 7px;">
                        <div class="progress-bar bg-or" style="width: {{ (int) max(3, min(100, $t->charge * 7)) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="small text-muted mb-0">—</p>
            @endforelse
        </div>

        <div class="section-title"><h2 class="h6"><i class="bi bi-trophy text-or"></i> Modèles les plus demandés</h2></div>
        <div class="stat-card">
            @forelse ($topModels as $m)
                <div class="d-flex justify-content-between small py-1 border-bottom">
                    <span>{{ $m->title }}</span><strong>{{ $m->orders_count }}</strong>
                </div>
            @empty
                <p class="small text-muted mb-0">—</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
