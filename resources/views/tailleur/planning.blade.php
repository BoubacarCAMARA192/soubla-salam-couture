@extends('layouts.app')

@section('title', 'Planning')
@section('page_title', 'Planning — '.$monthLabel)

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="d-flex gap-2">
        <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.planning', ['month' => ($month === 1 ? 12 : $month - 1), 'year' => ($month === 1 ? $year - 1 : $year)]) }}">
            <i class="bi bi-chevron-left"></i>
        </a>
        <span class="fw-bold text-bordeaux px-2 py-1">{{ $monthLabel }}</span>
        <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.planning', ['month' => ($month === 12 ? 1 : $month + 1), 'year' => ($month === 12 ? $year + 1 : $year)]) }}">
            <i class="bi bi-chevron-right"></i>
        </a>
        <a class="btn btn-sm btn-outline-bordeaux rounded-pill" href="{{ route('tailleur.planning') }}">Ce mois</a>
    </div>
    <button class="btn btn-bordeaux rounded-pill ms-auto" data-bs-toggle="collapse" data-bs-target="#newRdv">
        <i class="bi bi-plus-circle"></i> Programmer un rendez-vous
    </button>
</div>

{{-- Formulaire de création de RDV (TAI-05) --}}
<div class="collapse {{ $errors->any() ? 'show' : '' }}" id="newRdv">
    <div class="stat-card mb-4">
        @include('partials.errors')
        <form method="POST" action="{{ route('tailleur.appointments.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="type">Objet <span class="text-danger">*</span></label>
                    <select id="type" name="type" required class="form-select">
                        @foreach (\App\Models\Appointment::TYPES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="client_id">Client <span class="text-danger">*</span></label>
                    <select id="client_id" name="client_id" required class="form-select">
                        <option value="">— Choisir —</option>
                        @foreach ($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->full_name }} {{ $c->phone ? '· '.$c->phone : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="tailleur_id">Atelier</label>
                    <select id="tailleur_id" name="tailleur_id" class="form-select">
                        <option value="">— Moi —</option>
                        @foreach ($tailleurs as $t)
                            @if ($t->id !== auth()->id())
                                <option value="{{ $t->id }}">{{ $t->full_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label" for="scheduled_at">Date & heure <span class="text-danger">*</span></label>
                    <input id="scheduled_at" name="scheduled_at" type="datetime-local" required class="form-control" value="{{ old('scheduled_at') }}">
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="notes">Note (255 max)</label>
                    <input id="notes" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="ex : apporter le bazin bleu">
                </div>
            </div>
            <button class="btn btn-bordeaux mt-3 rounded-pill"><i class="bi bi-check2"></i> Ajouter (rappel auto au client)</button>
        </form>
    </div>
</div>

{{-- Liste chronologique du mois : rendez-vous + livraisons --}}}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="section-title"><h2 class="h5"><i class="bi bi-calendar-event text-or"></i> Rendez-vous du mois</h2></div>
        @foreach ($appointments as $rdv)
            <div class="stat-card py-2 px-3 mb-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span>
                        <strong>{{ $rdv->scheduled_at->translatedFormat('D d/m · H\hi') }}</strong>
                        — {{ $rdv->typeLabel() }},
                        <a class="text-bordeaux" href="{{ route('tailleur.clients.show', $rdv->client) }}">{{ $rdv->client->full_name }}</a>
                        @if ($rdv->tailleur) <span class="small text-muted">· {{ $rdv->tailleur->full_name }}</span> @endif
                    </span>
                    <span class="d-flex gap-1 align-items-center">
                        <span class="badge {{ $rdv->status === 'done' ? 'bg-success' : ($rdv->status === 'cancelled' ? 'bg-secondary' : 'bg-or text-dark') }}">
                            {{ ['planned' => 'à venir', 'done' => 'effectué', 'cancelled' => 'annulé'][$rdv->status] ?? $rdv->status }}
                        </span>
                    </span>
                </div>
                @if ($rdv->notes)
                    <div class="small text-muted mb-0 mt-1"><i class="bi bi-chat-left"></i> {{ $rdv->notes }}</div>
                @endif
                <div class="d-flex gap-2 mt-1">
                    <form method="POST" action="{{ route('tailleur.appointments.update', $rdv) }}" class="d-inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="done">
                        <button class="btn btn-sm btn-outline-success py-0" @if($rdv->status === 'done') disabled @endif><i class="bi bi-check-lg"></i> Fermer RDV</button>
                    </form>
                    <form method="POST" action="{{ route('tailleur.appointments.destroy', $rdv) }}" class="d-inline" onsubmit="return confirm('Annuler ce rendez-vous ?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger py-0"><i class="bi bi-x-lg"></i> Annuler</button>
                    </form>
                </div>
            </div>
        @endforeach

        {{-- RDV prochains hors du mois affiché — rappel en bas de liste --}}
        @if ($appointments->isEmpty() && $upcoming->isEmpty())
            <div class="stat-card text-center py-3 small text-muted">Aucun rendez-vous ce mois-ci.</div>
        @endif
        <div class="section-title"><h2 class="h5"><i class="bi bi-hourglass text-or"></i> Après ce mois</h2></div>
        @php
            $afterThisMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
        @endphp
        @forelse ($upcoming->filter(fn ($a) => \Carbon\Carbon::parse($a->scheduled_at) > $afterThisMonth) as $rdv)
            <div class="stat-card py-2 px-3 mb-2 d-flex justify-content-between small">
                <span>{{ $rdv->typeLabel() }} · {{ $rdv->client->full_name }}</span>
                <span class="text-bordeaux">{{ \App\Helpers\Date::label($rdv->scheduled_at) }}</span>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">—</div>
        @endforelse
    </div>

    <div class="col-lg-6">
        <div class="section-title"><h2 class="h5"><i class="bi bi-truck text-or"></i> Livraisons du mois</h2></div>
        @forelse ($deliveries as $o)
            <div class="stat-card py-2 px-3 mb-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span>
                        <strong>{{ $o->delivery_date->translatedFormat('D d/m') }}</strong>
                        — <a class="text-bordeaux" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a>
                        {{ $o->client->full_name }}
                    </span>
                    <span class="d-flex gap-2 align-items-center">
                        <span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span>
                        @if ($o->remaining_amount > 0)
                            <span class="badge bg-danger">{{ number_format($o->remaining_amount, 0, ',', ' ') }} F à encaisser</span>
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="stat-card text-center py-3 small text-muted">Aucune livraison prévue ce mois-ci.</div>
        @endforelse
    </div>
</div>
@endsection

