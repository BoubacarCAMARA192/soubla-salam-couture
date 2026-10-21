@extends('layouts.app')

@section('title', 'Commandes atelier')
@section('page_title', 'Gestion des commandes')

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <form method="GET" action="{{ route('tailleur.orders.index') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 420px;">
        <input name="q" value="{{ $q }}" class="form-control" placeholder="Référence, nom du client, tissu…">
        <button class="btn btn-bordeaux"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('tailleur.orders.create') }}" class="btn btn-bordeaux rounded-pill ms-auto">
        <i class="bi bi-plus-circle"></i> Nouvelle commande
    </a>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <a class="btn btn-sm {{ empty($statusFilter) ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('tailleur.orders.index') }}">
        Toutes <span class="badge bg-white text-dark ms-1">{{ array_sum($statusTotals) }}</span>
    </a>
    @foreach (\App\Models\Order::STATUSES as $key => $info)
        <a class="btn btn-sm {{ $statusFilter === $key ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('tailleur.orders.index', ['status' => $key]) }}">
            {{ $info[0] }} <span class="badge bg-white text-dark ms-1">{{ $statusTotals[$key] ?? 0 }}</span>
        </a>
    @endforeach
</div>

@if ($orders->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="bi bi-inbox display-6 text-or"></i>
        <p class="mt-2 mb-0">Aucune commande dans ce filtre.</p>
    </div>
@else
    <div class="table-responsive table-cards">
        <table class="table align-middle ssc-table">
            <thead>
                <tr>
                    <th>Réf.</th><th>Client</th><th>Tissu</th><th class="d-none d-lg-table-cell">Recu</th><th>Montant</th><th>Statut</th><th>Livraison</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $o)
                    <tr>
                        <td><a class="fw-bold text-bordeaux" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a></td>
                        <td>{{ $o->client->full_name }}</td>
                        <td class="small">{{ \Illuminate\Support\Str::limit($o->fabric ?? '—', 24) }}</td>
                        <td class="d-none d-lg-table-cell small text-muted">{{ $o->created_at->format('d/m/Y') }}</td>
                        <td class="nowrap">
                            {{ number_format($o->amount, 0, ',', ' ') }} F
                            @if ($o->amount > 0)
                                <br><span class="small {{ $o->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">{{ $o->remaining_amount > 0 ? 'reste '.number_format($o->remaining_amount, 0, ',', '').' F' : 'soldée' }}</span>
                            @endif
                        </td>
                        <td><span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span></td>
                        <td class="{{ $o->is_late ? 'text-danger fw-bold' : '' }}">{{ $o->delivery_date?->format('d/m/Y') ?? '—' }} @if($o->is_late)<i class="bi bi-exclamation-triangle"></i>@endif</td>
                        <td class="text-nowrap">
                            <a href="{{ route('tailleur.orders.show', $o) }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-pencil-square"></i> Suivre</a>
                            <a href="{{ route('tailleur.invoice', $o) }}" class="btn btn-sm btn-outline-bordeaux" title="Facture"><i class="bi bi-file-earmark-pdf"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif
@endsection
