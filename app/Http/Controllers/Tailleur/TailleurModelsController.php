<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\CatalogModel;
use App\Models\ModelPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TailleurModelsController extends Controller
{
    /** Proposition et modification de modèles du catalogue (TAI-07) */
    public function index()
    {
        $models = CatalogModel::with('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('tailleur.models.index', [
            'models' => $models,
        ]);
    }

    public function create()
    {
        return view('tailleur.models.create', [
            'model' => new CatalogModel,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $model = CatalogModel::create($data + ['created_by' => $request->user()->id]);
        $this->savePhotos($request, $model);

        \App\Models\ActivityLog::record('model.create', 'catalog_model', $model->id, ['titre' => $model->title]);

        return redirect()->route('tailleur.models.index')
            ->with('success', "Modèle « {$model->title} » enregistré en brouillon. Il sera publié par l'administrateur (ou directement via l'administration).");
    }

    public function edit(CatalogModel $catalogModel)
    {
        $catalogModel->load('photos');

        return view('tailleur.models.edit', ['model' => $catalogModel]);
    }

    public function update(Request $request, CatalogModel $catalogModel)
    {
        $data = $this->validated($request, $catalogModel);
        $catalogModel->update($data);
        $this->savePhotos($request, $catalogModel);

        \App\Models\ActivityLog::record('model.update', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);

        return redirect()->route('tailleur.models.index')->with('success', 'Modèle mis à jour.');
    }

    public function destroy(CatalogModel $catalogModel)
    {
        \App\Models\ActivityLog::record('model.delete', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);
        $catalogModel->delete();

        return redirect()->route('tailleur.models.index')->with('success', 'Modèle supprimé.');
    }

    protected function savePhotos(Request $request, CatalogModel $model): void
    {
        if (!$request->hasFile('photos')) {
            return;
        }

        $position = $model->photos()->count();
        foreach ($request->file('photos') as $photo) {
            if ($photo->isValid() && $position < 5) {
                $path = $photo->store('models', 'public');
                $model->photos()->create([
                    'path' => $path,
                    'position' => $position,
                ]);
                $position++;
            }
        }
    }

    protected function validated(Request $request, ?CatalogModel $existing = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:3000'],
            'category' => ['required', 'in:'.implode(',', CatalogModel::CATEGORIES)],
            'type' => ['required', 'in:'.implode(',', CatalogModel::TYPES)],
            'occasion' => ['required', 'in:'.implode(',', CatalogModel::OCCASIONS)],
            'suggested_fabric' => ['nullable', 'string', 'max:150'],
            'suggested_colors' => ['nullable', 'string', 'max:150'],
            'indicative_price' => ['required', 'numeric', 'min:0'],
            'delay_days' => ['nullable', 'integer', 'between:0,365'],
            'photos' => ['nullable', 'max:5'],
            'photos.*' => ['image', 'max:4096'],
        ], [
            'title.required' => 'Indiquez un titre pour le modèle.',
            'occasion.required' => 'Choisissez l\'occasion (Korité, mariage…).',
            'photos.image' => 'Chaque photo doit être un fichier image (JPG, PNG, WebP).',
            'photos.max' => 'Jusqu\'à 5 photos par modèle.',
            'photos.*.max' => 'Chaque photo doit peser moins de 4 Mo.',
        ]);

        $slug = Str::slug($data['title']);
        $data['slug'] = CatalogModel::where('slug', $slug)
            ->where('id', '!=', $existing?->id)
            ->exists() ? $slug.'-'.Str::lower(Str::random(4)) : $slug;

        unset($data['photos']);

        return $data;
    }
}
