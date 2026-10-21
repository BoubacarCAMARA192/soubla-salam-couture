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
                    <span class="small text-muted">Photos existantes ({{ $model->photos->count() }}/5)</span>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach ($model->photos as $p)
                            <a href="{{ asset('storage/'.$p->path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$p->path) }}" class="rounded-3 border" style="width: 82px; height: 82px; object-fit: cover;" alt="Photo {{ $p->position }}">
                            </a>
                        @endforeach
                    </div>
                    <div class="small text-muted mt-1">Ajoutez-en plus ci-dessous si nécessaire (5 max au total).</div>
                </div>
            @endif

            @include('tailleur.models._form', ['model' => $model])
        </div>
        <form method="POST" action="{{ route('tailleur.models.destroy', $model) }}" class="text-center mt-3" onsubmit="return confirm('Supprimer définitivement ce modèle ?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Supprimer le modèle</button>
        </form>
    </div>
</div>
@endsection
