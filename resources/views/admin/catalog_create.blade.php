@extends('layouts.app')

@section('title', 'Nouveau modèle (admin)')
@section('page_title', 'Créer un modèle de catalogue')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card p-4">
            @include('partials.errors')

            <form method="POST" action="{{ route('admin.catalog.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="t1">Titre <span class="text-danger">*</span></label>
                    <input id="t1" name="title" required class="form-control" value="{{ old('title') }}" placeholder="ex : Ensemble brodé Korité">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="c1">Pour <span class="text-danger">*</span></label>
                        <select id="c1" name="category" required class="form-select">
                            @foreach (\App\Models\CatalogModel::CATEGORY_LABELS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="ty1">Style <span class="text-danger">*</span></label>
                        <select id="ty1" name="type" required class="form-select">
                            @foreach (\App\Models\CatalogModel::TYPES as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="oc1">Occasion <span class="text-danger">*</span></label>
                        <select id="oc1" name="occasion" required class="form-select">
                            @foreach (\App\Models\CatalogModel::OCCASIONS as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="d1">Description</label>
                    <textarea id="d1" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="p1">Prix moyen (FCFA) <span class="text-danger">*</span></label>
                        <input id="p1" name="indicative_price" type="number" min="0" step="500" required class="form-control" value="{{ old('indicative_price') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="dl1">Délai (jours)</label>
                        <input id="dl1" name="delay_days" type="number" min="0" max="365" class="form-control" value="{{ old('delay_days') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="f1">Tissu conseillé</label>
                        <input id="f1" name="suggested_fabric" class="form-control" value="{{ old('suggested_fabric') }}">
                    </div>
                </div>
                <div class="mb-2">
                    <input id="cols1" name="suggested_colors" class="form-control" placeholder="Couleurs conseillées (ex : bordeaux, or)" value="{{ old('suggested_colors') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ph1">Photos (max 5)</label>
                    <input id="ph1" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="form-control">
                </div>
                <div class="form-check mb-3">
                    <input id="pub1" class="form-check-input" type="checkbox" name="publish_now" checked>
                    <label class="form-check-label" for="pub1">Publier immédiatement sur le portail</label>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-check2"></i> Enregistrer</button>
                    <a href="{{ route('admin.catalog.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
