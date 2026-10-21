@extends('layouts.app')

@section('title', 'Recherche')
@section('page_title', 'Recherche')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="stat-card p-3 mb-4">
            <form method="GET" action="{{ route('tailleur.search') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="q">Mot-clé (nom, téléphone, tissu, référence…)</label>
                    <input id="q" name="q" class="form-control" value="{{ $q }}" placeholder="ex : Fatou, bazin bleu, SSC-26-…">
                </div>
                <div class="col-md-4 d-grid">
                    <button class="btn btn-bordeaux"><i class="bi bi-search"></i> Chercher</button>
                </div>
            </form>
        </div>

        @if ($q === '')
            <div class="stat-card text-center py-4 small text-muted">Saisissez un mot-clé pour retrouver un client, une commande ou un modèle…</div>
        @else
            @if ($clients->count() === 0 && $orders->count() === 0 && $models->count() === 0)
                <div class="stat-card text-center py-5">
                    <i class="bi bi-binoculars display-6 text-or"></i>
                    <p class="mt-2 mb-0">Aucun résultat pour « {{ $q }} ».</p>
                </div>
            @endif

            @if ($clients->count())
                <div class="section-title"><h2 class="h5"><i class="bi bi-people text-or"></i> Clients ({{ $clients->count() }})</h2></div>
                @foreach ($clients->take(8) as $c)
                    <div class="stat-card py-2 px-3 mb-2 d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-person-circle text-or"></i>
                            <a class="text-bordeaux fw-bold" href="{{ route('tailleur.clients.show', $c) }}">{{ $c->full_name }}</a>
                            <span class="small text-muted">{{ $c->phone ?? '' }}</span>
                        </span>
                        <span class="small text-muted">{{ $c->orders_count }} commande(s)</span>
                    </div>
                @endforeach
            @endif

            @if ($orders->count())
                <div class="section-title"><h2 class="h5"><i class="bi bi-bag text-or"></i> Commandes ({{ $orders->count() }})</h2></div>
                @foreach ($orders->take(8) as $o)
                    <div class="stat-card py-2 px-3 mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span>
                            <i class="bi bi-bag text-or"></i>
                            <a class="text-bordeaux fw-bold" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a>
                            <span class="small text-muted">{{ $o->client->full_name }} @if($o->fabric) · {{ \Illuminate\Support\Str::limit($o->fabric, 18) }} @endif</span>
                        </span>
                        <span class="d-flex gap-2 align-items-center">
                            <span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
                            <span class="small">{{ number_format($o->amount, 0, ',', ' ') }} F</span>
                        </span>
                    </div>
                @endforeach
            @endif

            @if ($models->count())
                <div class="section-title"><h2 class="h5"><i class="bi bi-collection text-or"></i> Modèles ({{ $models->count() }})</h2></div>
                <div class="row g-2 mb-3">
                    @foreach ($models->take(8) as $m)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('tailleur.models.edit', $m) }}" class="stat-card d-block text-center py-2 text-decoration-none text-dark">
                                <i class="bi bi-collection {{ $m->status === 'published' ? 'text-success' : 'text-muted' }}"></i>
                                <div class="small">{{ \Illuminate\Support\Str::limit($m->title, 26) }}</div>
                                <div class="small text-muted">{{ ['draft' => 'brouillon', 'published' => 'publié', 'retired' => 'retiré'][$m->status] ?? $m->status }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
