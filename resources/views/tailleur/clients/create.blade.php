@extends('layouts.app')

@section('title', 'Créer une fiche client')
@section('page_title', 'Nouveau client')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="stat-card p-4">
            @include('partials.errors')

            <form method="POST" action="{{ route('tailleur.clients.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="full_name">Nom complet <span class="text-danger">*</span></label>
                    <input id="full_name" name="full_name" type="text" required class="form-control" value="{{ old('full_name') }}" placeholder="ex : Fatou Fall">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Téléphone</label>
                        <input id="phone" name="phone" type="tel" class="form-control" value="{{ old('phone') }}" placeholder="ex : 77 555 22 33">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="address">Adresse (facultatif)</label>
                        <input id="address" name="address" type="text" class="form-control" value="{{ old('address') }}" placeholder="ex : Fass, rue 12">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="notes">Notes préférences & historique</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="ex : préfère les manches 3/4, bazin bleu foncé…">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-check2"></i> Créer la fiche</button>
                    <a href="{{ route('tailleur.clients.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
