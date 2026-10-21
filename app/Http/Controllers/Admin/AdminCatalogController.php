<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogModel;
use App\Models\ModelPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCatalogController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $models = CatalogModel::with(['photos', 'creator'])
            ->when($status && in_array($status, CatalogModel::STATUSES), fn ($q) => $q->where('status', $status))
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.catalog', ['models' => $models, 'statusFilter' => $status]);
    }

    public function create()
    {
        return view('admin.catalog_create', ['model' => new CatalogModel]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $model = CatalogModel::create($data + ['created_by' => $request->user()->id]);
        $this->savePhotos($request, $model);

        if ($request->boolean('publish_now')) {
            $model->update(['status' => 'published']);
            \App\Models\ActivityLog::record('model.publish', 'catalog_model', $model->id, ['titre' => $model->title]);
        }

        \App\Models\ActivityLog::record('model.create', 'catalog_model', $model->id, ['titre' => $model->title]);

        return redirect()->route('admin.catalog.index')->with('success', "Modèle « {$model->title} » enregistré.");
    }

    public function edit(CatalogModel $catalogModel)
    {
        $catalogModel->load('photos');

        return view('admin.catalog_edit', ['model' => $catalogModel]);
    }

    public function update(Request $request, CatalogModel $catalogModel)
    {
        $data = $this->validated($request, $catalogModel);
        $catalogModel->update($data);
        $this->savePhotos($request, $catalogModel);

        \App\Models\ActivityLog::record('model.update', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);

        return redirect()->route('admin.catalog.index')->with('success', 'Modèle mis à jour.');
    }

    /** Publication (AMD-02) : le catalogue est retirable en une action */
    public function publish(CatalogModel $catalogModel)
    {
        $catalogModel->update(['status' => 'published']);

        \App\Models\ActivityLog::record('model.publish', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);

        return back()->with('success', "« {$catalogModel->title} » est maintenant publié sur le portail.");
    }

    public function retire(CatalogModel $catalogModel)
    {
        $catalogModel->update(['status' => 'retired', 'is_featured' => false]);

        \App\Models\ActivityLog::record('model.retire', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);

        return back()->with('success', "« {$catalogModel->title} » a été retiré du portail public.");
    }

    /** Mise en avant sur la page d'accueil (CAT-08) */
    public function feature(CatalogModel $catalogModel)
    {
        $catalogModel->update(['is_featured' => !$catalogModel->is_featured]);

        \App\Models\ActivityLog::record('model.feature', 'catalog_model', $catalogModel->id, [
            'titre' => $catalogModel->title,
            'state' => $catalogModel->is_featured,
        ]);

        return back()->with('success', $catalogModel->is_featured
            ? "« {$catalogModel->title} » est mis en avant sur le portail."
            : "« {$catalogModel->title} » n'apparaît plus en page d'accueil.");
    }

    public function destroy(CatalogModel $catalogModel)
    {
        \App\Models\ActivityLog::record('model.delete', 'catalog_model', $catalogModel->id, ['titre' => $catalogModel->title]);
        $catalogModel->delete();

        return redirect()->route('admin.catalog.index')->with('success', 'Modèle supprimé.');
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
                $model->photos()->create(['path' => $path, 'position' => $position]);
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
            'status' => ['nullable', 'in:'.implode(',', CatalogModel::STATUSES)],
            'photos' => ['nullable'],
            'photos.*' => ['image', 'max:4096'],
        ]);

        $slug = Str::slug($data['title']);
        $data['slug'] = CatalogModel::where('slug', $slug)
            ->where('id', '!=', $existing?->id)
            ->exists() ? $slug.'-'.Str::lower(Str::random(4)) : $slug;

        unset($data['photos']);

        return $data;
    }
}
