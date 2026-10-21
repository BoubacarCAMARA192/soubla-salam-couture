@extends('layouts.public')

@section('title', 'Confidentialité')

@section('content')
<div class="container page-wrap py-5" style="max-width: 860px;">
    <h1 class="h2 font-decor text-bordeaux"><i class="bi bi-shield-lock"></i> Confidentialité et données personnelles</h1>
    <p class="text-muted">Conformément à la loi sénégalaise n° 2008-12 sur la protection des données à caractère personnel.</p>

    <div class="stat-card p-4">
        <h2 class="h5 font-decor text-bordeaux">Quelles données sont collectées ?</h2>
        <ul>
            <li>Les coordonnées que vous inscrivez (nom, prénom, téléphone, e-mail, adresse).</li>
            <li>Vos mensurations si vous passez commande (saisies par le tailleur lors du rendez-vous).</li>
            <li>L'historique de vos commandes, paiements et échanges avec l'atelier.</li>
        </ul>

        <h2 class="h5 font-decor text-bordeaux mt-4">À quoi servent-elles ?</h2>
        <p>Gérer votre commande de couture, vous rappeler les rendez-vous, établir vos reçus et améliorer le service de l'atelier.
            Vos mensurations ne sont accessibles qu'à vous et aux couturiers de l'atelier.</p>

        <h2 class="h5 font-decor text-bordeaux mt-4">Vos droits</h2>
        <p>Vous pouvez consulter, mettre à jour ou demander la suppression de vos données depuis votre espace client ou auprès de l'atelier : <a href="mailto:{{ \App\Models\Setting::get('atelier_email') }}">{{ \App\Models\Setting::get('atelier_email') }}</a>.</p>

        <h2 class="h5 font-decor text-bordeaux mt-4">Sécurité</h2>
        <p>Votre mot de passe est chiffré (hachage bcrypt), la connexion est sécurisée par HTTPS et les accès sont tracés dans un journal d'activité.</p>
    </div>
</div>
@endsection
