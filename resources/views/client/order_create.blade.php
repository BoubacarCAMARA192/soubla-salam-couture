@extends('layouts.app')

@section('title', 'Nouvelle demande')
@section('page_title', $mode === 'quote' ? 'Demander un devis' : 'Nouvelle commande')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card p-4">
            <h1 class="h5 font-decor text-bordeaux">
                <i class="bi bi-scissors"></i>
                {{ $mode === 'quote' ? 'Demander un devis' : 'Commander la confection' }}
            </h1>

            @include('partials.flash')
            @include('partials.errors')

            @if ($model)
                <div class="wash-note d-flex align-items-center gap-3 mb-4">
                    @if ($model->mainPhoto())
                        <img src="{{ asset('storage/'.$model->mainPhoto()->path) }}" class="rounded-3" style="width: 74px; height: 74px; object-fit: cover;" alt="">
                    @endif
                    <div class="flex-grow-1">
                        <strong>{{ $model->title }}</strong><br>
                        <span class="small text-muted">{{ $model->type }} — {{ $model->occasion }}</span>
                    </div>
                    @if ($model->indicative_price)
                        <span class="badge bg-bordeaux">≈ {{ number_format($model->indicative_price, 0, ',', ' ') }} F</span>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('client.orders.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $mode }}">
                <input type="hidden" name="catalog_model_id" value="{{ $model?->id }}">

                <div class="mb-3">
                    <label class="form-label" for="description">
                        Décrivez votre tenue <span class="text-danger">*</span>
                    </label>
                    @if ($mode === 'quote')
                        <p class="small text-muted mb-1">Exemple : « même robe que la photo mais en bazin bleu, longueur cheville, manches longues avec broderie dorée ».</p>
                    @endif
                    <textarea id="description" name="description" rows="4" required
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="fabric">Tissu souhaité (si vous l'avez)</label>
                        <input id="fabric" name="fabric" type="text" class="form-control" placeholder="ex : bazin getzner bleu, wax, rips" value="{{ old('fabric') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="event_date">Date de l'événement (facultatif)</label>
                        <input id="event_date" name="event_date" type="date" class="form-control" value="{{ old('event_date') }}" min="{{ now()->addDays(2)->format('Y-m-d') }}">
                        <div class="form-text">L'atelier préviendra si le délai est trop court.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="inspiration_photo">Photo d'inspiration (facultatif)</label>
                        <input id="inspiration_photo" name="inspiration_photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('inspiration_photo') is-invalid @enderror">
                        <div class="form-text">JPG, PNG ou WebP — 3 Mo maximum.</div>
                        @error('inspiration_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="wash-note mt-4 mb-3">
                    <i class="bi bi-info-circle text-or"></i>
                    Aucun paiement n'est demandé à cette étape : l'atelier vous répond avec un devis,
                    puis un rendez-vous de mesures sera fixé. L'acompte ne se paie qu'après validation (reçu remis).
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-bordeaux rounded-pill flex-grow-1">
                        <i class="bi bi-send"></i> Envoyer la demande de {{ $mode === 'quote' ? 'devis' : 'commande' }}
                    </button>
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


