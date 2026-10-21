@extends('layouts.app')

@section('title', 'Clients / carnet')
@section('page_title', 'Carnet clients')

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <form method="GET" action="{{ route('tailleur.clients.index') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 420px;">
        <input name="q" value="{{ $search }}" class="form-control" placeholder="Nom ou téléphone…">
        <button class="btn btn-bordeaux"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('tailleur.clients.create') }}" class="btn btn-bordeaux rounded-pill ms-auto">
        <i class="bi bi-person-plus"></i> Nouveau client
    </a>
</div>

@if ($clients->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="bi bi-people display-6 text-or"></i>
        <p class="mt-2 mb-0">Aucun client trouvé.</p>
    </div>
@else
    <div class="table-responsive table-cards">
        <table class="table align-middle ssc-table">
            <thead><tr><th>Nom</th><th>Téléphone</th><th>Type</th><th>Commandes</th><th>Dernière visite</th><th></th></tr></thead>
            <tbody>
                @foreach ($clients as $c)
                    <tr>
                        <td><a class="fw-bold text-bordeaux" href="{{ route('tailleur.clients.show', $c) }}">{{ $c->full_name }}</a></td>
                        <td>{{ $c->phone ?? '—' }}</td>
                        <td>
                            @if ($c->user_id)
                                <span class="badge bg-or text-dark">compte client</span>
                            @else
                                <span class="badge text-bg-light border">passage</span>
                            @endif
                        </td>
                        <td>{{ $c->orders_count }}</td>
                        <td class="small text-muted">{{ $c->last_visit_at?->format('d/m/Y') ?? $c->first_visit_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.clients.edit', $c) }}"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('tailleur.clients.destroy', $c) }}" class="d-inline"
                                  onsubmit="return confirm('Supprimer définitivement la fiche de {{ $c->full_name }} ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $clients->links() }}
@endif
@endsection
