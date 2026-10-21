@extends('layouts.app')

@section('title', 'Modifier '.$client->full_name)
@section('page_title', 'Modifier le client — '.$client->full_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="stat-card p-4">
            @include('partials.errors')
            <form method="POST" action="{{ route('tailleur.clients.update', $client) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="full_name">Nom complet <span class="text-danger">*</span></label>
                    <input id="full_name" name="full_name" type="text" required class="form-control" value="{{ old('full_name', $client->full_name) }}">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Téléphone</label>
                        <input id="phone" name="phone" type="tel" class="form-control" value="{{ old('phone', $client->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="address">Adresse</label>
                        <input id="address" name="address" type="text" class="form-control" value="{{ old('address', $client->address) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="notes">Notes préférences & historique</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $client->notes) }}</textarea>
                    </div>
                </div>
                @if ($client->user)
                    <div class="small text-muted mt-2">
                        <i class="bi bi-info-circle"></i> Compte client associé : {{ $client->user->full_name }} ({{ $client->user->email }}) —
                        le téléphone devient identifiant de connexion.
                    </div>
                @endif
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-save"></i> Enregistrer</button>
                    <a href="{{ route('tailleur.clients.show', $client) }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
