@extends('layouts.public')

@section('title', 'Contact')

@section('content')
<div class="container page-wrap py-4">
    <h1 class="h2 font-decor text-bordeaux"><i class="bi bi-telephone"></i> Nous contacter</h1>
    <p class="lead">Réponse rapide par WhatsApp, au téléphone ou à l'atelier.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <a class="text-decoration-none" href="tel:{{ \App\Models\Setting::get('atelier_phone') }}">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-telephone-fill display-6 text-bordeaux"></i>
                    <p class="fw-semibold mb-1 mt-2">Téléphone</p>
                    <p class="small text-muted mb-0">{{ \App\Models\Setting::get('atelier_phone') }}</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a class="text-decoration-none" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('atelier_whatsapp')) }}" target="_blank" rel="noopener">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-whatsapp display-6" style="color: #25d366;"></i>
                    <p class="fw-semibold mb-1 mt-2">WhatsApp</p>
                    <p class="small text-muted mb-0">{{ \App\Models\Setting::get('atelier_whatsapp') }} — envoyer une photo d'inspiration</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a class="text-decoration-none" href="mailto:{{ \App\Models\Setting::get('atelier_email') }}">
                <div class="stat-card text-center h-100">
                    <i class="bi bi-envelope-fill display-6 text-bordeaux"></i>
                    <p class="fw-semibold mb-1 mt-2">E-mail</p>
                    <p class="small text-muted mb-0">{{ \App\Models\Setting::get('atelier_email') }}</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="stat-card">
                <h2 class="h6 font-decor text-bordeaux">Écrire à l'atelier</h2>
                <p class="small text-muted">Sans compte : votre message crée une fiche de passage et l'atelier vous répond par téléphone ou WhatsApp.</p>
                @if (session('success'))
                    <div class="alert alert-success small"><i class="bi bi-check2-circle"></i> {{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small" for="last_name">Nom *</label>
                            <input id="last_name" name="last_name" required maxlength="50" class="form-control" value="{{ old('last_name') }}">
                            @error('last_name')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small" for="first_name">Prénom *</label>
                            <input id="first_name" name="first_name" required maxlength="50" class="form-control" value="{{ old('first_name') }}">
                            @error('first_name')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small" for="phone">Téléphone / WhatsApp *</label>
                            <input id="phone" name="phone" required maxlength="15" class="form-control" placeholder="77 001 01 01" value="{{ old('phone') }}">
                            @error('phone')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small" for="email">E-mail (facultatif)</label>
                            <input id="email" name="email" type="email" maxlength="100" class="form-control" value="{{ old('email') }}">
                            @error('email')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small" for="subject">Sujet</label>
                            <input id="subject" name="subject" maxlength="100" class="form-control" placeholder="ex : tenue pour mariage en décembre" value="{{ old('subject') }}">
                            @error('subject')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small" for="body">Message *</label>
                            <textarea id="body" name="body" rows="4" required class="form-control" placeholder="Votre besoin : modèle, tissu, date de l'événement…">{{ old('body') }}</textarea>
                            @error('body')<div class="small text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-send"></i> Envoyer le message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stat-card h-100">
                <h2 class="h6 font-decor text-bordeaux">Adresse & horaires</h2>
                <p class="mb-1"><i class="bi bi-geo-alt text-or"></i> {{ \App\Models\Setting::get('atelier_address') }}</p>
                <p class="small mb-3" style="white-space: pre-line;"><i class="bi bi-clock text-or"></i> {{ \App\Models\Setting::get('atelier_hours') }}</p>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <iframe src="https://maps.google.com/maps?q=Sacre-Coeur-3%20Dakar&output=embed" loading="lazy" title="Carte"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="wash-note h-100 d-flex flex-column justify-content-center">
                <h2 class="h6 font-decor text-bordeaux">Une question sur une commande ?</h2>
                <p class="small mb-2">Si vous êtes déjà cliente ou client, le plus rapide est de <a href="{{ route('login') }}">vous connecter</a> et d'utiliser la messagerie rattachée à votre commande : l'atelier vous répond depuis votre suivi.</p>
                <p class="small mb-0">Pas encore de compte ? <a href="{{ route('register') }}">Créez-le en 2 minutes</a> — aucune inscription nécessaire pour visiter le catalogue, mais commandes, devis et rendez-vous se gèrent à partir d'un compte.</p>
            </div>
        </div>
    </div>
</div>
@endsection
