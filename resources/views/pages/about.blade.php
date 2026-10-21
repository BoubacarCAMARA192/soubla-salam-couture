@extends('layouts.public')

@section('title', "L'atelier")

@section('content')
<div class="container page-wrap py-4">
    <h1 class="h2 font-decor text-bordeaux"><i class="bi bi-house-heart"></i> L'atelier Soubla Salam Couture</h1>
    <p class="lead" style="white-space: pre-line;">{{ \App\Models\Setting::get('about_text') }}</p>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="stat-card h-100 text-center">
                <i class="bi bi-scissors display-6 text-or"></i>
                <h2 class="h6 font-decor text-bordeaux mt-2">Couture à la main</h2>
                <p class="small mb-0">Broderie, couture, retouches : chaque tenue est confectionnée pièce par pièce, mesures exactes, finitions soignées.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100 text-center">
                <i class="bi bi-rulers display-6 text-or"></i>
                <h2 class="h6 font-decor text-bordeaux mt-2">Sur mesure</h2>
                <p class="small mb-0">Vos mensurations sont enregistrées une fois et consultables depuis votre espace client, à chaque commande.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100 text-center">
                <i class="bi bi-calendar2-heart display-6 text-or"></i>
                <h2 class="h6 font-decor text-bordeaux mt-2">Fêtes & cérémonies</h2>
                <p class="small mb-0">Korité, Tabaski, mariage, baptême : pensez à commander tôt, les places de couture partent vite en période de fêtes !</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-7">
            <div class="stat-card h-100">
                <h2 class="h6 font-decor text-bordeaux">Le déroulé d'une commande</h2>
                <ol class="mb-0">
                    <li class="mb-2">Vous choisissez un modèle ou décrivez votre projet (photo d'inspiration possible).</li>
                    <li class="mb-2">L'atelier vous répond avec un devis et un rendez-vous de mesures.</li>
                    <li class="mb-2">Vous validez avec un acompte — reçu remis ou téléchargeable.</li>
                    <li class="mb-2">Vous suivez chaque étape (coupe, couture, essayage) depuis votre espace.</li>
                    <li>Vous récupérez la tenue réglée, avec le reçu final.</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="stat-card h-100">
                <h2 class="h6 font-decor text-bordeaux">Nous rendre visite</h2>
                <ul class="list-unstyled small mb-3">
                    <li class="mb-2"><i class="bi bi-geo-alt text-or"></i> {{ \App\Models\Setting::get('atelier_address') }}</li>
                    <li class="mb-2"><i class="bi bi-clock text-or"></i> <span style="white-space: pre-line;">{{ \App\Models\Setting::get('atelier_hours') }}</span></li>
                    <li class="mb-2"><i class="bi bi-telephone text-or"></i> {{ \App\Models\Setting::get('atelier_phone') }}</li>
                    <li><i class="bi bi-envelope text-or"></i> {{ \App\Models\Setting::get('atelier_email') }}</li>
                </ul>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <iframe src="https://maps.google.com/maps?q=Sacre-Coeur-3%20Dakar&output=embed" loading="lazy" title="Carte"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
