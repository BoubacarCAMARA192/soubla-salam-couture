@extends('layouts.app')

@section('title', 'Mes rendez-vous')
@section('page_title', 'Mes rendez-vous')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="section-title"><h2 class="h5"><i class="bi bi-calendar2-check text-or"></i> Rendez-vous à venir</h2></div>

        @if ($appUpcoming->isEmpty())
            <div class="stat-card text-center py-4">
                <i class="bi bi-calendar-week display-6 text-or"></i>
                <p class="mt-2 mb-0">Aucun rendez-vous programmé.</p>
            </div>
        @else
            @foreach ($appUpcoming as $rdv)
                <div class="stat-card mb-2">
                    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                        <div>
                            <strong>{{ $rdv->typeLabel() }}</strong>
                            @if ($rdv->order)
                                <a class="badge text-bg-light border text-decoration-none" href="{{ route('client.orders.show', $rdv->order) }}">{{ $rdv->order->reference }}</a>
                            @endif
                            @if ($rdv->status === 'cancelled')
                                <span class="badge bg-secondary">annulé</span>
                            @endif
                        </div>
                        <span class="text-bordeaux fw-semibold">
                            <i class="bi bi-clock"></i> {{ \App\Helpers\Date::label($rdv->scheduled_at) }}
                        </span>
                    </div>
                    @if ($rdv->notes)
                        <p class="small text-muted mb-0 mt-1"><i class="bi bi-chat-left"></i> {{ $rdv->notes }}</p>
                    @endif
                </div>
            @endforeach
            @include('partials.flash')
        @endif
    </div>

    <div class="col-lg-5">
        <div class="section-title"><h2 class="h5"><i class="bi bi-plus-circle text-or"></i> Demander un rendez-vous</h2></div>
        <div class="stat-card">
            @include('partials.errors')
            <form method="POST" action="{{ route('client.appointments.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="type">Objet du rendez-vous <span class="text-danger">*</span></label>
                    <select id="type" name="type" required class="form-select">
                        @foreach (\App\Models\Appointment::TYPES as $key => $label)
                            <option value="{{ $key }}" @if(old('type') === $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="scheduled_at">Date & heure souhaitées <span class="text-danger">*</span></label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at" required
                           class="form-control @error('scheduled_at') is-invalid @enderror"
                           value="{{ old('scheduled_at') }}" min="{{ now()->addHours(4)->format('Y-m-d\TH:i') }}"
                           max="{{ now()->addMonth()->format('Y-m-d\TH:i') }}">
                    <div class="form-text">Sur proposition, dans le mois à venir. L'atelier vous confirmera.</div>
                    @error('scheduled_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="order_id">Commande concernée (facultatif)</label>
                    <select id="order_id" name="order_id" class="form-select">
                        <option value="">— Aucune (nouvelle demande) —</option>
                        @foreach ($orders as $o)
                            <option value="{{ $o->id }}" @if(old('order_id') == $o->id) selected @endif>
                                {{ $o->reference }} — {{ \App\Models\Order::statusLabel($o->status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="notes">Précisions (facultatif)</label>
                    <textarea id="notes" name="notes" rows="2" class="form-control" maxlength="255" placeholder="ex : besoin d'un essayage après le travail">{{ old('notes') }}</textarea>
                </div>

                <button class="btn btn-bordeaux rounded-pill w-100"><i class="bi bi-send"></i> Envoyer ma demande</button>
            </form>
        </div>

        <div class="wash-note mt-3">
            <i class="bi bi-lightbulb text-or"></i>
            <strong>Attention :</strong> un rendez-vous de mesures dure environ 20 minutes —
            venez avec votre tissu si l'atelier ne le fournit pas.
        </div>
    </div>
</div>
@endsection
