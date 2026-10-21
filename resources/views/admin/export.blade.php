@extends('layouts.app')

@section('title', 'Exports & sauvegarde')
@section('page_title', 'Exports & sauvegarde (ADM-07)')

@section('content')
@include('partials.flash')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="section-title"><h2 class="h5"><i class="bi bi-file-earmark-arrow-down text-or"></i> Export CSV annoté</h2></div>
        <div class="stat-card">
            <div class="list-group list-group-flush">
                @foreach ([
                    'clients' => ['people', 'Clients', 'compte client + historique'],
                    'orders' => ['basket', 'Commandes', 'toutes les commandes et statuts intermédiaires'],
                    'payments' => ['cash-stack', 'Paiements', 'reçus et modes de paiement'],
                    'models' => ['collection', 'Catalogue', 'modèles et vues/commandes'],
                    'expenses' => ['receipt', 'Dépenses', 'charges mensuelles'],
                    'activity' => ['journal-text', 'Journal d\'activité', 'titres référence — copier/utiliser un grand écran'],
                ] as $type => list($icon, $label, $desc))
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('admin.export.download', $type) }}">
                        <span><i class="bi bi-{{ $icon }} text-or me-2"></i><strong>{{ $label }}</strong> <span class="small text-muted">— {{ $desc }}</span></span>
                        <i class="bi bi-download text-bordeaux"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="section-title"><h2 class="h5"><i class="bi bi-database-check text-or"></i> Sauvegarde complète</h2></div>
        <div class="stat-card">
            <p class="small">
                Sauvegarde petit atelier : sa place est une seule commande. En <strong>SQLite</strong>, le fichier de base portable
                est copiable d'un clic ; sur MySQL, la commande <code>mysqldump</code> produit la sauvegarde (voir README).
            </p>
            <a href="{{ route('admin.backup') }}" class="btn btn-bordeaux w-100 rounded-pill">
                <i class="bi bi-download"></i> Télécharger le fichier de base
            </a>
        </div>

        <div class="wash-note mt-3">
            <i class="bi bi-lightbulb text-or"></i>
            <strong>Cycle conseillé :</strong> un export hebdomadaire des paiements +<br>
            une sauvegarde complète en fin de mois.
        </div>
    </div>
</div>
@endsection
