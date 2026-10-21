@extends('layouts.public')

@section('title', 'Connexion')

@section('content')
<div class="container py-5" style="max-width: 480px;">
    <div class="card card-ssc p-4">
        <div class="text-center mb-4">
            <i class="bi bi-scissors fs-1 text-or"></i>
            <h1 class="h4 font-decor text-bordeaux mb-0">Retrouvez votre atelier</h1>
            <p class="text-muted small">Suivez vos commandes, rendez-vous et mesures.</p>
        </div>

        @include('partials.flash')
        @include('partials.errors')

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="login">E-mail ou téléphone</label>
                <input id="login" type="text" name="login" required autofocus
                       class="form-control @error('login') is-invalid @enderror"
                       value="{{ old('login') }}" placeholder="ex : 77 000 00 00">
                @error('login') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required
                       class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Rester connecté</label>
                </div>
                <a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            </div>

            <button class="btn btn-bordeaux w-100 rounded-pill mb-3"><i class="bi bi-box-arrow-in-right"></i> Se connecter</button>
        </form>

        <p class="text-center small mb-0">
            Pas encore de compte ? <a href="{{ route('register') }}" class="fw-semibold">Créez-le gratuitement</a>
        </p>
    </div>
</div>
@endsection
