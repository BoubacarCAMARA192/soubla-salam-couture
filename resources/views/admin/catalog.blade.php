@extends('layouts.app')

@section('title', 'Catalogue — modération')
@section('page_title', 'Catalogue (ADM-02)')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-sm {{ empty($statusFilter) ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('admin.catalog.index') }}">Tous</a>
        @foreach (['draft' => 'brouillons', 'published' => 'publiés', 'retired' => 'retirés'] as $key => $label)
            <a class="btn btn-sm {{ $statusFilter === $key ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('admin.catalog.index', ['status' => $key]) }}">{{ $label }}</a>
        @endforeach
    </div>
    <a href="{{ route('admin.catalog.create') }}" class="btn btn-bordeaux rounded-pill"><i class="bi bi-plus-circle"></i> Nouveau modèle</a>
</div>

@forelse ($models as $m)
    <div class="stat-card mb-3">
        <div class="d-flex flex-wrap gap-3">
            @if ($m->mainPhoto())
                <img src="{{ asset('storage/'.$m->mainPhoto()->path) }}" class="rounded-3" style="width: 110px; height: 110px; object-fit: cover;" alt="{{ $m->title }}">
            @else
                <div class="rounded-3 bg-creme d-flex align-items-center justify-content-center" style="width: 110px; height: 110px;">
                    <i class="bi bi-image fs-3 text-muted"></i>
                </div>
            @endif

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <strong class="fs-6">{{ $m->title }}</strong>
                    @if ($m->status === 'draft') <span class="badge bg-secondary">brouillon</span>
                    @elseif ($m->status === 'published') <span class="badge bg-success">publié</span>
                    @else <span class="badge bg-warning text-dark">retiré</span> @endif
                    @if ($m->is_featured) <span class="badge bg-or text-dark"><i class="bi bi-star-fill"></i> en avant</span> @endif
                </div>
                <div class="small text-muted mt-1">{{ $m->category_label }} · {{ $m->type }} · {{ $m->occasion }} · ≈ {{ number_format($m->indicative_price, 0, ',', ' ') }} F · créé par {{ $m->creator?->full_name ?? '—' }}</div>
                @if ($m->description) <p class="small mb-0 mt-1">{{ \Illuminate\Support\Str::limit($m->description, 140) }}</p> @endif
            </div>

            <div class="d-flex flex-column gap-1 flex-nowrap justify-content-center">
                @if ($m->status !== 'published')
                    <form method="POST" action="{{ route('admin.catalog.publish', $m) }}">
                        @csrf
                        <button class="btn btn-sm btn-success"><i class="bi bi-upload"></i> Publier</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.catalog.retire', $m) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-down"></i> Retirer</button>
                    </form>
                    <form method="POST" action="{{ route('admin.catalog.feature', $m) }}">
                        @csrf
                        <button class="btn btn-sm {{ $m->is_featured ? 'btn-warning' : 'btn-outline-bordeaux' }}">
                            <i class="bi bi-star"></i> {{ $m->is_featured ? 'Retirer de l\'avant' : 'Mettre en avant' }}
                        </button>
                    </form>
                @endif
                <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('admin.catalog.edit', $m) }}"><i class="bi bi-pencil"></i> Modifier</a>
            </div>
        </div>
    </div>
@empty
    <div class="stat-card text-center py-5 small text-muted">Aucun modèle dans ce filtre.</div>
@endforelse

{{ $models->links() }}
@endsection
