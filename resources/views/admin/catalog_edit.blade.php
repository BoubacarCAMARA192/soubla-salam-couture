@extends('layouts.app')

@section('title', 'Modifier « '.$model->title.' »')
@section('page_title', 'Modifier le modèle')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card p-4">
            @include('partials.errors')

            @if ($model->photos->count())
                <div class="mb-3">
                    <span class="small text-muted">Photos ({{ $model->photos->count() }}/5) :</span>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach ($model->photos as $p)
                            <a href="{{ asset('storage/'.$p->path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$p->path) }}" class="rounded-3 border" style="width: 86px; height: 86px; object-fit: cover;">
                            </a>
                        @endforeach
                    </div>
                    <div class="small text-muted mt-1">Pour en ajouter jusqu'à 5 en tout, utilisez le champ ci-dessous.</div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.catalog.update', $model) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="tt2">Titre <span class="text-danger">*</span></label>
                    <input id="tt2" name="title" required class="form-control" value="{{ old('title', $model->title) }}">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="cc2">Pour</label>
                        <select id="cc2" name="category" class="form-select">
                            @foreach (\App\Models\CatalogModel::CATEGORY_LABELS as $key => $label)
                                <option value="{{ $key }}" @if(old('category', $model->category) === $key) selected @endif>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="type2">Style</label>
                        <select id="type2" name="type" class="form-select">
                            @foreach (\App\Models\CatalogModel::TYPES as $t)
                                <option value="{{ $t }}" @if(old('type', $model->type) === $t) selected @endif>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="occ2">Occasion</label>
                        <select id="occ2" name="occasion" class="form-select">
                            @foreach (\App\Models\CatalogModel::OCCASIONS as $o)
                                <option value="{{ $o }}" @if(old('occasion', $model->occasion) === $o) selected @endif>{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="desc2">Description</label>
                    <textarea id="desc2" name="description" rows="3" class="form-control">{{ old('description', $model->description) }}</textarea>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="price2">Prix moyen (FCFA)</label>
                        <input id="price2" name="indicative_price" type="number" min="0" step="500" required class="form-control" value="{{ old('indicative_price', $model->indicative_price) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="delay2">Délai (jours)</label>
                        <input id="delay2" name="delay_days" type="number" min="0" max="365" class="form-control" value="{{ old('delay_days', $model->delay_days) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="fabr2">Tissu conseillé</label>
                        <input id="fabr2" name="suggested_fabric" class="form-control" value="{{ old('suggested_fabric', $model->suggested_fabric) }}">
                    </div>
                </div>
                <div class="mb-2">
                    <input id="col2" name="suggested_colors" class="form-control" value="{{ old('suggested_colors', $model->suggested_colors) }}" placeholder="Couleurs conseillées">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="photos2">Ajouter des photos (max 5 au total)</label>
                    <input id="photos2" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="form-control">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-save"></i> Enregistrer</button>
                    <a href="{{ route('admin.catalog.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.catalog.destroy', $model) }}" class="text-center mt-4 pt-3 border-top" onsubmit="return confirm('Supprimer définitivement ce modèle ?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection
