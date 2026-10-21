@extends('layouts.app')

@section('title', $client->full_name)
@section('page_title', 'Fiche client — '.$client->full_name)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
    <div>
        <h2 class="h4 font-decor text-bordeaux mb-0">{{ $client->full_name }}</h2>
        <span class="small text-muted">
            @if ($client->user_id) <span class="badge bg-or text-dark">Client enregistré ({{ $client->user?->full_name }})</span> @else <span class="badge text-bg-light border">Client de passage</span> @endif
            {{ $client->phone ? '· '.$client->phone : '' }} {{ $client->last_visit_at ? '· dernière visite le '.$client->last_visit_at->format('d/m/Y') : '' }}
        </span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.clients.edit', $client) }}"><i class="bi bi-pencil"></i> Modifier</a>
        <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.measurements.pdf', $client) }}"><i class="bi bi-file-pdf"></i> Fiche mensurations</a>
        <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.orders.create') }}"><i class="bi bi-plus-circle"></i> Commande</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        {{-- Prise de mesures (TAI-03) : zone de saisie principale --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-rulers text-or"></i> Relever des mesures</h2></div>

        <div class="stat-card">
            @include('partials.flash')
            @include('partials.errors')
            <form method="POST" action="{{ route('tailleur.measurements.store', $client) }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small" for="clothing_type">Type de vêtement <span class="text-danger">*</span></label>
                        <select id="clothing_type" name="clothing_type" required class="form-select">
                            <option value="">— Choisir —</option>
                            @foreach ($measurements as $type => $existing)
                                <option value="{{ $type }}" @if(old('clothing_type') === $type) selected @endif>{{ $type }} (reprendre)</option>
                            @endforeach
                            @foreach (array_diff(\App\Models\ClothingType::activeNames(), $measurements->keys()->all()) as $t)
                                <option value="{{ $t }}" @if(old('clothing_type') === $t) selected @endif>{{ $t }} (nouveau)</option>
                            @endforeach
                            <option value="Autre" @if(old('clothing_type') === 'Autre') selected @endif>Autre…</option>
                        </select>
                    </div>
                    <div class="col-md-6"></div>
                    @foreach (['chest' => 'POITRINE', 'waist' => 'TAILLE', 'hips' => 'HANCHES', 'shoulders' => 'ÉPAULES', 'sleeve_length' => 'MANCHE', 'top_length' => 'LONG.CORPS', 'pants_length' => 'LONG.PANT.', 'thigh' => 'CUISSE'] as $field => $short)
                        <div class="col-6 col-md-3">
                            <label class="form-label small" for="{{ $field }}">{{ $short }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" max="999" step="0.5" class="form-control" value="{{ old($field) }}" placeholder="{{ $short === 'POITRINE' ? '86' : '' }}">
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 small text-muted">Mesures personnalisées (max 5) :</div>
                <div class="row g-2 mt-1">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="col-6">
                            <input name="extra_labels[{{ $i }}]" class="form-control form-control-sm" placeholder="ex : long. boubou">
                        </div>
                        <div class="col-6">
                            <input name="extra_values[{{ $i }}]" type="number" step="0.5" class="form-control form-control-sm" placeholder="valeur">
                        </div>
                    @endfor
                </div>

                <textarea name="notes" rows="2" class="form-control mt-3" placeholder="Notes : posture, préférences de coupe…">{{ old('notes') }}</textarea>

                <button class="btn btn-bordeaux mt-3"><i class="bi bi-rulers"></i> Enregistrer les mensurations</button>
            </form>
        </div>

        @if ($client->notes)
            <div class="wash-note mt-3">
                <i class="bi bi-chat-left-quote text-or"></i> <strong>Préférences :</strong> {{ $client->notes }}
            </div>
        @endif

        {{-- RDV à venir --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-calendar2-check text-or"></i> Rendez-vous</h2></div>
        <div class="stat-card">
            @if ($upcomingAppointments->isEmpty())
                <p class="small text-muted mb-0">Aucun rendez-vous à venir.</p>
            @else
                @foreach ($upcomingAppointments as $rdv)
                    <div class="d-flex justify-content-between align-items-center small py-2 border-bottom">
                        <span>{{ $rdv->typeLabel() }}</span>
                        <span class="text-bordeaux">{{ \App\Helpers\Date::label($rdv->scheduled_at) }} · {{ $rdv->status }}</span>
                    </div>
                @endforeach
            @endif
            <a href="{{ route('tailleur.planning') }}" class="btn btn-sm btn-outline-bordeaux mt-2 rounded-pill">Planning complet</a>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="section-title"><h2 class="h5"><i class="bi bi-card-list text-or"></i> Historique des mensurations ({{ $client->measurements()->count() }})</h2></div>
        @if ($client->measurements()->count() === 0)
            <div class="stat-card text-center py-4 small text-muted">Aucune mensuration enregistrée.</div>
        @else
            @foreach ($measurements as $type => $records)
                <div class="stat-card py-2 px-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-bordeaux">{{ $type }}</strong>
                        <span class="small text-muted">{{ $records->count() }} prise(s)</span>
                    </div>
                    @foreach ($records->take(3) as $m)
                        <div class="d-flex justify-content-between align-items-center small py-1 border-bottom">
                            <span>dernière : {{ $m->created_at->format('d/m/Y') }} · {{ $m->takenBy?->full_name ?? '—' }}</span>
                            <span class="d-flex gap-2 align-items-center">
                                <span class="badge text-bg-light border">P{{ $m->chest ?? '?' }} · T{{ $m->waist ?? '?' }} · H{{ $m->hips ?? '?' }}</span>
                                <form method="POST" action="{{ route('tailleur.measurements.destroy', $m) }}" class="d-inline" onsubmit="return confirm('Supprimer cette prise ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0"><i class="bi bi-x"></i></button>
                                </form>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif

        {{-- Commandes et paiements --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-bag text-or"></i> Commandes ({{ $ordersCount }}) — {{ number_format($totalPaid, 0, ',', ' ') }} F encaissés</h2></div>
        <div class="table-responsive table-cards">
            <table class="table table-sm ssc-table align-middle">
                <thead><tr><th>Réf.</th><th>Tissu</th><th>Montant</th><th>Statut</th></tr></thead>
                <tbody>
                    @forelse ($client->orders()->orderBy('created_at', 'desc')->take(10)->get() as $o)
                        <tr>
                            <td><a class="text-bordeaux" href="{{ route('tailleur.orders.show', $o) }}">{{ $o->reference }}</a></td>
                            <td class="small">{{ \Illuminate\Support\Str::limit((string) $o->fabric, 16) }}</td>
                            <td class="nowrap">{{ number_format($o->amount, 0, ',', ' ') }} F</td>
                            <td><span class="badge" style="background-color: {{ \App\Models\Order::statusColor($o->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted small">Aucune commande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
