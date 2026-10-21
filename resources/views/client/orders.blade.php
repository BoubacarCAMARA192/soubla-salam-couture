@extends('layouts.app')

@section('title', 'Mes commandes')
@section('page_title', 'Mes commandes')

@section('content')
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('client.orders.create') }}" class="btn btn-bordeaux rounded-pill">
        <i class="bi bi-plus-circle"></i> Demande sur mesure
    </a>
    <div class="ms-auto d-flex gap-2 flex-wrap">
        <a class="btn btn-sm {{ empty($statusFilter) ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('client.orders.index') }}">
            Toutes
        </a>
        @foreach (\App\Models\Order::STATUSES as $key => $info)
            <a class="btn btn-sm {{ $statusFilter === $key ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('client.orders.index', ['status' => $key]) }}">
                {{ $info[0] }}
            </a>
        @endforeach
    </div>
</div>

@if ($orders->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="bi bi-inboxes display-5 text-or"></i>
        <p class="mt-3">Aucune commande dans cette liste.</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-bordeaux rounded-pill">Découvrir le catalogue</a>
    </div>
@else
    @foreach ($orders as $o)
        <div class="stat-card mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div class="flex-grow-1">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="fw-bold text-bordeaux fs-6">{{ $o->reference }}</span>
                        <span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#888' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
                        @if ($o->is_late) <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> retard</span> @endif
                    </div>
                    <p class="small text-muted mb-0 mt-1">
                        {{ $o->type === 'quote' ? 'Demande de devis' : 'Commande' }}
                        — passée le {{ $o->created_at->format('d/m/Y') }}
                        @if ($o->catalogModel) — modèle « {{ $o->catalogModel->title }} » @endif
                    </p>
                </div>
                <div class="text-end">
                    <div class="price">{{ number_format($o->amount, 0, ',', ' ') }} F</div>
                    @if ($o->amount > 0)
                        <span class="small {{ $o->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $o->remaining_amount > 0 ? 'reste '.number_format($o->remaining_amount, 0, ',', ' ').' F' : 'soldée' }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="mt-2">
                @include('partials.timeline', ['order' => $o])
            </div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <a href="{{ route('client.orders.show', $o) }}" class="btn btn-sm btn-bordeaux"><i class="bi bi-eye"></i> Suivre / écrire</a>
                <a href="{{ route('client.orders.pdf', $o) }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-file-pdf"></i> Documents</a>
                @if ($o->status === \App\Models\Order::REQUESTED)
                    <span class="btn btn-sm btn-outline-bordeaux disabled"><i class="bi bi-hourglass"></i> En attente de l'atelier</span>
                @endif
            </div>
        </div>
    @endforeach
    {{ $orders->links() }}
@endif
@endsection
