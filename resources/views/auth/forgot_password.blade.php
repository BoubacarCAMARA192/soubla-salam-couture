@extends('layouts.public')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card card-ssc p-4 text-center">
        <i class="bi bi-key fs-1 text-or"></i>
        <h1 class="h4 font-decor text-bordeaux">Récupérer l'accès</h1>

        @include('partials.flash')
        @include('partials.errors')

        <form method="POST" action="{{ route('password.email') }}" class="mt-3">
            @csrf
            <p class="text-muted small">Indiquez l'e-mail de votre compte : un lien de réinitialisation vous sera envoyé.</p>
            <input type="email" name="email" required class="form-control mb-3" placeholder="e-mail du compte" value="{{ old('email') }}">
            <button class="btn btn-bordeaux w-100 rounded-pill"><i class="bi bi-send"></i> Envoyer le lien</button>
        </form>
        <p class="small mt-3 mb-0"><a href="{{ route('login') }}">Retour à la connexion</a></p>
    </div>
</div>
@endsection
