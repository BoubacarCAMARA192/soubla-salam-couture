@extends('layouts.public')

@section('title', 'Inscription')

@section('content')
<div class="container py-5" style="max-width: 560px;">
    <div class="card card-ssc p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-plus fs-1 text-or"></i>
            <h1 class="h4 font-decor text-bordeaux mb-0">Créer mon compte client</h1>
            <p class="text-muted small">Commander, suivre mes tenues et garder mes mesures.</p>
        </div>

        @include('partials.flash')
        @include('partials.errors')

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label" for="first_name">Prénom</label>
                    <input id="first_name" type="text" name="first_name" required class="form-control" value="{{ old('first_name') }}">
                </div>
                <div class="col-6">
                    <label class="form-label" for="last_name">Nom</label>
                    <input id="last_name" type="text" name="last_name" required class="form-control" value="{{ old('last_name') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="phone">Téléphone (numéro de connexion)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input id="phone" type="tel" name="phone" required class="form-control" value="{{ old('phone') }}" placeholder="+221 77 000 00 00">
                    </div>
                    <div class="form-text">Vous le servira aussi à vous connecter à la place de l'e-mail.</div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="email">E-mail</label>
                    <input id="email" type="email" name="email" required class="form-control" value="{{ old('email') }}">
                    <div class="form-text">Il servira aux notifications et reçus.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password">Mot de passe (8 caractères min.)</label>
                    <input id="password" type="password" name="password" required minlength="8" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirmez</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="consent" id="consent" required>
                        <label class="form-check-label small" for="consent">
                            J'accepte le traitement de mes données pour ma confection : <a href="{{ route('privacy') }}" target="_blank">confidentialité (loi n° 2008-12)</a>
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-bordeaux w-100 rounded-pill"><i class="bi bi-check2"></i> Finaliser l'inscription</button>
                </div>
                <div class="col-12 text-center small">
                    Déjà cliente ? <a href="{{ route('login') }}" class="fw-semibold">Connectez-vous</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
