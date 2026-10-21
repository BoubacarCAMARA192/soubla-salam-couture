<form method="POST" enctype="multipart/form-data"
      action="{{ $model->exists ? route('tailleur.models.update', $model) : route('tailleur.models.store') }}">
    @csrf
    @if ($model->exists) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label" for="title">Titre du modèle <span class="text-danger">*</span></label>
        <input id="title" name="title" type="text" required class="form-control" value="{{ old('title', $model->title) }}" placeholder="ex : Robe bazin brodée dorée">
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label" for="category">Pour <span class="text-danger">*</span></label>
            <select id="category" name="category" required class="form-select">
                @foreach (\App\Models\CatalogModel::CATEGORY_LABELS as $key => $label)
                    <option value="{{ $key }}" @if(old('category', $model->category) === $key) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="type">Style <span class="text-danger">*</span></label>
            <select id="type" name="type" required class="form-select">
                @foreach (\App\Models\CatalogModel::TYPES as $t)
                    <option value="{{ $t }}" @if(old('type', $model->type) === $t) selected @endif>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="occasion">Occasion <span class="text-danger">*</span></label>
            <select id="occasion" name="occasion" required class="form-select">
                @foreach (\App\Models\CatalogModel::OCCASIONS as $o)
                    <option value="{{ $o }}" @if(old('occasion', $model->occasion) === $o) selected @endif>{{ $o }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="description_t">Descripme du modèle</label>
        <textarea id="description_t" name="description" rows="3" class="form-control" placeholder="coupe, finitions, détails de broderie…">{{ old('description', $model->description) }}</textarea>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label" for="indicative_price">Prix moyen (FCFA) <span class="text-danger">*</span></label>
            <input id="indicative_price" name="indicative_price" type="number" min="0" step="500" required class="form-control" value="{{ old('indicative_price', $model->indicative_price) }}">
            <div class="form-text">Ce prix sert d'aide pour les devis (faction FIF-06) mais n'est pas contractuel.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="delay_days">Délai indicatif (jours)</label>
            <input id="delay_days" name="delay_days" type="number" min="0" max="365" class="form-control" value="{{ old('delay_days', $model->delay_days) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="suggested_fabric">Tissu conseillé</label>
            <input id="suggested_fabric" name="suggested_fabric" class="form-control" value="{{ old('suggested_fabric', $model->suggested_fabric) }}" placeholder="bazin, wax, percale…">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="suggested_colors">Couleurs conseillées</label>
        <input id="suggested_colors" name="suggested_colors" class="form-control" value="{{ old('suggested_colors', $model->suggested_colors) }}" placeholder="ex : bordeaux, doré, ivoire">
    </div>

    <div class="mb-3">
        <label class="form-label" for="photos">Photos (jusqu'à 5 — JPG/PNG/WebP, 4 Mo max chacune)</label>
        <input id="photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="form-control @error('photos.*') is-invalid @enderror">
        @error('photos.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        @error('photos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        <div class="form-text">La première photo choisie devient la photo principale de la fiche de détail.</div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-bordeaux rounded-pill">
            <i class="bi bi-check2"></i> {{ $model->exists ? 'Enregistrer' : 'Créer le modèle (brouillon)' }}
        </button>
        <a href="{{ route('tailleur.models.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
    </div>
</form>
