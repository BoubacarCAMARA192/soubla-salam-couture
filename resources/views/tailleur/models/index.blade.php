@extends('layouts.app')

@section('title', 'Mes modèles')
@section('page_title', 'Mes modèles de catalogue (TAI-07)')

@section('content')
<div class="d-flex gap-2 justify-content-between align-items-center mb-3">
    <span class="small text-muted">Vos propositions passent en « brouillon ». L'administrateur les publie ensuite dans le catalogue public.</span>
    <a href="{{ route('tailleur.models.create') }}" class="btn btn-bordeaux rounded-pill"><i class="bi bi-plus-circle"></i> Nouveau modèle</a>
</div>

<div class="row g-3">
    @forelse ($models as $m)
        <div class="col-6 col-lg-3">
            <div class="stat-card p-2 text-center h-100">
                @if ($m->mainPhoto())
                    <img src="{{ asset('storage/'.$m->mainPhoto()->path) }}" class="rounded-3" style="width: 100%; height: 130px; object-fit: cover;" alt="{{ $m->title }}">
                @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 100%; height: 130px; background: var(--creme);">
                        <i class="bi bi-image fs-4 text-muted"></i>
                    </div>
                @endif
                <div class="text-start mt-2 px-1">
                    <strong class="d-block">{{ \Illuminate\Support\Str::limit($m->title, 34) }}</strong>
                    <span class="small text-muted">{{ $m->category_label }} · {{ $m->occasion }}</span><br>
                    <span class="badge {{ $m->status === 'published' ? 'bg-success' : ($m->status === 'draft' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                        {{ ['draft' => 'brouillon', 'published' => 'publié', 'retired' => 'retiré'][$m->status] ?? $m->status }}
                    </span>
                    <span class="badge text-bg-light border">≈ {{ number_format($m->indicative_price, 0, ',', ' ') }} F</span>
                </div>
                <div class="d-flex gap-1 mt-2">
                    <a class="btn btn-sm btn-outline-bordeaux flex-grow-1" href="{{ route('tailleur.models.edit', $m) }}"><i class="bi bi-pencil"></i> Modifier</a>
                    <form method="POST" action="{{ route('tailleur.models.destroy', $m) }}" onsubmit="return confirm('Supprimer ce modèle ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="stat-card text-center py-5">
                <i class="bi bi-collection display-6 text-or"></i>
                <p class="mt-2 mb-0">Aucun modèle enregistré. Créez votre première proposition.</p>
            </div>
        </div>
    @endforelse
</div>

{{ $models->links() }}
@endsection
