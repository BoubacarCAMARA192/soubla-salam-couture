@extends('layouts.app')

@section('title', 'Nouveau modèle')
@section('page_title', 'Proposer un modèle (TAI-07)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card p-4">
            @include('partials.errors')
            @include('tailleur.models._form', ['model' => $model])
        </div>
    </div>
</div>
@endsection
