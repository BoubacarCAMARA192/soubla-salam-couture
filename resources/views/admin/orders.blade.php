@extends('layouts.app')

@section('title', 'Supervision commandes')
@section('page_title', 'Supervision des commandes (ADM-03)')

@section('content')
<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex flex-wrap gap-2">
        <input type="hidden" name="status" value="{{ $statusFilter }}">
        <input type="hidden" name="tailleur" value="{{ $tailleurFilter }}">
        <input name="q" value="{{ $q }}" class="form-control" style="width: 260px;" placeholder="RÃ©f. ou clientâ€¦">
        <button class="btn btn-bordeaux"><i class="bi bi-search"></i></button>
    </form>

    <select class="form-select flex-grow-0" style="width: 220px;" onchange="const u = new URL(location.href); u.searchParams.set('tailleur', this.value); u.searchParams.delete('page'); location.href = u;">
        <option value="">Tous les couturiers</option>
        @foreach ($tailleurs as $t)
            <option value="{{ $t->id }}" @if($tailleurFilter == $t->id) selected @endif>{{ $t->full_name }}</option>
        @endforeach
    </select>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <a class="btn btn-sm {{ empty($statusFilter) ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('admin.orders.index') }}">Toutes <span class="badge bg-white text-dark ms-1">{{ array_sum($statusTotals) }}</span></a>
    @foreach (\App\Models\Order::STATUSES as $key => $info)
        <a class="btn btn-sm {{ $statusFilter === $key ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill"
           href="{{ route('admin.orders.index', array_filter(['status' => $key, 'tailleur' => $tailleurFilter, 'q' => $q])) }}">
            {{ $info[0] }} <span class="badge bg-white text-dark ms-1">{{ $statusTotals[$key] ?? 0 }}</span>
        </a>
    @endforeach
</div>

@if ($orders->isEmpty())
    <div class="stat-card text-center py-5 small text-muted">Aucune commande avec ces filtres.</div>
@else
    <div class="table-responsive table-cards">
        <table class="table align-middle ssc-table">
            <thead><tr><th>RÃ©f.</th><th>Client</th><th>Couturier</th><th>Statut</th><th>Montant</th><th>Ã‰chÃ©ance</th><th></th></tr></thead>
            <tbody>
                @foreach ($orders as $o)
                    <tr>
                        <td><a class="fw-bold text-bordeaux" href="{{ route('admin.orders.show', $o) }}">{{ $o->reference }}</a></td>
                        <td>{{ $o->client->full_name }}</td>
                        <td class="small">{{ $o->tailleur?->full_name ?? 'â€”' }}</td>
                        <td><span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span></td>
                        <td class="nowrap">{{ number_format($o->amount, 0, ',', ' ') }} F</td>
                        <td class="{{ $o->is_late ? 'text-danger fw-bold' : '' }}">{{ $o->delivery_date?->format('d/m/Y') ?? 'â€”' }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-bordeaux" data-bs-toggle="collapse" data-bs-target="#rea-{{ $o->id }}" title="RÃ©affecter"><i class="bi bi-arrow-left-right"></i></button>
                            @if ($o->status !== \App\Models\Order::CANCELLED)
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#ann-{{ $o->id }}" title="Annuler"><i class="bi bi-slash-circle"></i></button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="p-0">
                            {{-- RÃ©affectation (ADM-03) : compte actif ou inactif --}}
                            <form method="POST" action="{{ route('admin.orders.reassign', $o) }}" id="rea-{{ $o->id }}"
                                  class="collapse bg-creme p-2 d-flex gap-2 justify-content-end">
                                @csrf
                                <select name="tailleur_id" required class="form-select form-select-sm" style="width: 220px;">
                                    <option value="">â€” Nouvel atelier en charge â€”</option>
                                    @foreach ($tailleurs as $t)
                                        @if ($t->id !== $o->tailleur_id)
                                            <option value="{{ $t->id }}">{{ $t->full_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-bordeaux"><i class="bi bi-check2"></i> RÃ©affecter</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif

{{-- Modales d'annulation : justification obligatoire (ADM-03) --}}
@foreach ($orders as $o)
    @if ($o->status !== \App\Models\Order::CANCELLED)
        <div class="modal fade" id="ann-{{ $o->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <h3 class="h6 font-decor text-bordeaux">Annulation de {{ $o->reference }}</h3>
                    <p class="small text-muted">L'annulation est rÃ©servÃ©e aux administrateurs : la justification s'affichera dans le journal du client.</p>
                    <form method="POST" action="{{ route('admin.orders.cancel', $o) }}">
                        @csrf
                        <input name="comment" required maxlength="255" class="form-control mb-2" placeholder="Motif (ex : Ã©vÃ©nement reportÃ© par le client)">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-bordeaux" data-bs-dismiss="modal">Retour</button>
                            <button class="btn btn-danger"><i class="bi bi-slash-circle"></i> Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

