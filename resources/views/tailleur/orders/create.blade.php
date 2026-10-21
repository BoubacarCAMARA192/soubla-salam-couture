@extends('layouts.app')

@section('title', 'Nouvelle commande')
@section('page_title', 'Créer une commande (TAI-04)')

@section('content')
@if ($order)
    <div class="wash-note mb-3">
        <i class="bi bi-reply text-or"></i>
        Pré-rempli depuis la demande <strong>{{ $order->reference }}</strong> de {{ $order->client->full_name }} —
        <a href="{{ route('tailleur.orders.show', $order) }}">voir l'original</a>.
    </div>
@endif

<div class="stat-card p-4">
    @include('partials.errors')

    <form method="POST" action="{{ route('tailleur.orders.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="client_id">Client <span class="text-danger">*</span></label>
                <select id="client_id" name="client_id" required class="form-select">
                    <option value="">— Choisir —</option>
                    @forelse ($clients as $c)
                        <option value="{{ $c->id }}" @if(old('client_id', $order?->client_id) == $c->id) selected @endif>
                            {{ $c->full_name }} {{ $c->phone ? '· '.$c->phone : '' }}
                        </option>
                    @empty
                        <option disabled>Aucun client — créez-en un d'abord</option>
                    @endforelse
                </select>
                <div class="form-text d-flex justify-content-between">
                    <span>Client de passage ? Créez-le via un compte minimal.</span>
                    <a href="{{ route('tailleur.clients.create') }}">Créer un client <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="type">Type de saisie <span class="text-danger">*</span></label>
                <select id="type" name="type" class="form-select">
                    <option value="order" @if(old('type', 'order') === 'order') selected @endif>Commande (montant connu)</option>
                    <option value="quote" @if(old('type', $order?->type) === 'quote') selected @endif>Devis / estimation à définir</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label" for="description">Description de la tenue <span class="text-danger">*</span></label>
                <textarea id="description" name="description" rows="3" required class="form-control" placeholder="ex : boubou homme en bazin getzner, broderie col, manches courtes">{{ old('description', $order?->description) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="catalog_model_id">Modèle du catalogue (facultatif)</label>
                <select id="catalog_model_id" name="catalog_model_id" class="form-select" disabled>
                    <option value="">La recherche dynamique arrivera avec le catalogue</option>
                </select>
                <div class="small text-muted">Sélection possible une fois les modèles enregistrés (TAA-01).</div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="fabric">Tissu</label>
                <input id="fabric" name="fabric" list="fabricList" class="form-control" value="{{ old('fabric', $order?->fabric) }}" placeholder="bazin bleu, wax, rips…">
                <datalist id="fabricList">
                    @foreach ($fabrics as $f)
                        <option value="{{ $f->name }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="amount">Montant convenu (FCFA)</label>
                <input id="amount" name="amount" type="number" min="0" step="100" class="form-control"
                       value="{{ old('amount') }}">
                <div class="form-text">Pour un devis, laissez vide : fixez-le après accord client.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="delivery_date">Livraison prévue</label>
                <input id="delivery_date" name="delivery_date" type="date" class="form-control" value="{{ old('delivery_date') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="event_date">Date de l'événement</label>
                <input id="event_date" name="event_date" type="date" class="form-control" value="{{ old('event_date', $order?->event_date?->format('Y-m-d')) }}">
                <div class="form-text form-check mt-1">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label small text-muted">Date d'exigence : vérifier le délai</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="tailleur_id">Affecté à</label>
                <select id="tailleur_id" name="tailleur_id" class="form-select">
                    <option value="">— Moi ({{ auth()->user()->full_name }}) —</option>
                    @foreach (\App\Models\User::whereIn('role', ['tailleur','admin'])->where('status', 'active')->get() as $u)
                        @if ($u->id !== auth()->id())
                            <option value="{{ $u->id }}">{{ $u->full_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="tailleur_notes">Notes atelier (internes)</label>
                <textarea id="tailleur_notes" name="tailleur_notes" rows="2" class="form-control" placeholder="non visibles par le client">{{ old('tailleur_notes') }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            @if ($order)
                <input type="hidden" name="from_reference" value="{{ $order->reference }}">
            @endif
            <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-check2"></i> Créer la commande</button>
            <a href="{{ route('tailleur.orders.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
        </div>
    </form>
</div>
@endsection
