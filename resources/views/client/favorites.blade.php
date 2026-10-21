@extends('layouts.app')

@section('title', 'Mes favoris')
@section('page_title', 'Mes modèles favoris')

@section('content')
@if ($models->isEmpty())
    <div class="stat-card text-center py-5 m-auto" style="max-width: 520px;">
        <i class="bi bi-heartbreak display-5 text-or"></i>
        <p class="mt-3 mb-2">Vous n'avez pas encore de favoris.</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-bordeaux rounded-pill">
            <i class="bi bi-heart"></i> Parcourir le catalogue
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach ($models as $m)
            <div class="col-6 col-md-4 col-xl-3">
                @include('partials.model_card', ['m' => $m, 'isFavorite' => true])
            </div>
        @endforeach
    </div>
@endif
@endsection
