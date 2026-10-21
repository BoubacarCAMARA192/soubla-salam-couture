@extends('layouts.app')

@section('title', 'Contenu du portail')
@section('page_title', 'Contenu du portail public (ADM-08)')

@section('content')
@include('partials.flash')

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="stat-card p-4">
            <p class="small text-muted">
                <i class="bi bi-eye text-or"></i> Ces textes apparaissent directement sur les pages visibles par tout le monde :
                bannière, page d'accueil, menus, pied de page.
            </p>

            <form method="POST" action="{{ route('admin.portal.update') }}">
                @csrf
                @foreach ($portalKeys as $key => $label)
                    @php $current = $settings[$key]->value ?? \App\Models\Setting::defaults()[$key] ?? ''; @endphp
                    <div class="mb-3">
                        <label class="form-label" for="{{ $key }}">{{ $label }} <span class="text-muted">({{ $key }})</span></label>
                        @if (in_array($key, ['hero_subtitle', 'banner_text'], true))
                            <textarea id="{{ $key }}" name="{{ $key }}" rows="2" class="form-control" maxlength="300">{{ $current }}</textarea>
                        @else
                            <input id="{{ $key }}" name="{{ $key }}" class="form-control" maxlength="60" value="{{ $current }}">
                        @endif
                        @if ($key === 'banner_text')
                            <div class="form-text">Idéal pour : « Commandes Tabaski : tout dépôt avant le 30 avril ».</div>
                        @endif
                    </div>
                @endforeach

                <div class="wash-note mb-3">
                    <i class="bi bi-magic text-or"></i>
                    <strong>Exemple cohérent pour Tabaski :</strong>
                    bandeau = « Commandes Tabasski : tout dépôt avant le 30 avril »,
                    accroche = « Vos tenues de fête, taillées à la main à Dakar ».
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-bordeaux rounded-pill"><i class="bi bi-box-arrow-up-right"></i> Voir le portail</a>
                    <button class="btn btn-bordeaux rounded-pill ms-auto"><i class="bi bi-save"></i> Publier les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
