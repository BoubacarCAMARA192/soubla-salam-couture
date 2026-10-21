@extends('layouts.public')

@section('title', 'Réinitialisation du mot de passe')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card card-ssc p-4">
        <h1 class="h5 font-decor text-bordeaux text-center">Choisir un nouveau mot de passe</h1>

        @include('partials.flash')
        @include('partials.errors')

        <form method="POST" action="{{ route('password.update') }}" class="mt-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input id="email" type="email" name="email" required class="form-control" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Nouveau mot de passe</label>
                <input id="password" type="password" name="password" required minlength="8" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmez</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="form-control">
            </div>
            <button class="btn btn-bordeaux w-100 rounded-pill"><i class="bi bi-check2"></i> Enregistrer</button>
        </form>
    </div>
</div>
@endsection
