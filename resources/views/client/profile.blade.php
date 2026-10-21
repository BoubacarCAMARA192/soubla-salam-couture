@extends('layouts.app')

@section('title', 'Mon profil & mensurations')
@section('page_title', 'Mes mensurations & coordonnées')

@section('content')
<div class="row g-4">
    {{-- Coordonnées modifiables --}}
    <div class="col-lg-5">
        <div class="stat-card h-100">
            <h2 class="h6 font-decor text-bordeaux"><i class="bi bi-person"></i> Mes coordonnées</h2>
            @include('partials.flash')
            @include('partials.errors')
            <form method="POST" action="{{ route('client.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="phone">Téléphone</label>
                    <input id="phone" name="phone" type="tel" required class="form-control" value="{{ old('phone', $client->phone) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="address">Adresse (pour la livraison)</label>
                    <textarea id="address" name="address" rows="2" class="form-control">{{ old('address', $client->address) }}</textarea>
                </div>
                <div class="small text-muted mb-3">
                    <i class="bi bi-envelope"></i> {{ $user->email }} — <span class="text-success">e-mail vérifié via notifications</span>
                </div>
                <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-check2"></i> Mettre à jour</button>
            </form>
        </div>
    </div>

    {{-- Mensurations : consultation (mise à jour par le tailleur) CLI-02 --}}
    <div class="col-lg-7">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="h6 font-decor text-bordeaux"><i class="bi bi-rulers"></i> Mes mensurations</h2>
                <span class="badge bg-or text-dark">Mise à jour par l'atelier</span>
            </div>

            @if ($measurements->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-rulers display-5 text-or"></i>
                    <p class="mt-2 mb-2">Aucune mensuration enregistrée pour l'instant.</p>
                    <p class="small text-muted">Elles seront relevées par le tailleur mais renseignées lors du rendez-vous de mesures.</p>
                    <a href="{{ route('client.appointments') }}" class="btn btn-sm btn-outline-bordeaux rounded-pill">Demander un rendez-vous de mesures</a>
                </div>
            @else
                @foreach ($measurements as $type => $records)
                    <div class="mb-2">
                        <button class="btn w-100 d-flex justify-content-between align-items-center bg-creme border rounded-3 py-2"
                                data-bs-toggle="collapse" data-bs-target="#m{{ $loop->index }}">
                            <span class="fw-semibold text-bordeaux">{{ $type }}</span>
                            <span class="small text-muted">{{ $records->count() }} prise(s)</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse {{ $loop->first ? 'show' : '' }}" id="m{{ $loop->index }}">
                            @php $derniere = $records->first(); @endphp
                            <div class="p-3 bg-white border-top">
                                <div class="row g-2 small">
                                    @foreach ($derniere->filledFields() as $label => $value)
                                        <div class="col-6 col-md-4">
                                            <div class="bg-creme rounded-2 p-2">
                                                <span class="text-muted d-block">{{ $label }}</span>
                                                <strong>{{ $value }}</strong>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($derniere->notes)
                                    <p class="small text-muted mt-2 mb-0"><i class="bi bi-chat-left"></i> {{ $derniere->notes }}</p>
                                @endif
                                @if (count($records) > 1)
                                    <details class="mt-2 small">
                                        <summary class="text-muted">Historique ({{ count($records) }} prises)</summary>
                                        <ul>
                                            @foreach ($records->skip(1) as $anc)
                                                <li>Le {{ $anc->created_at->format('d/m/Y') }} — {{ collect($anc->filledFields())->implode(' ; ') }}</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @endif
                                <span class="small text-muted"><i class="bi bi-person-check"></i> Mesuré par {{ $derniere->takenBy?->full_name ?? 'l\'atelier' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
