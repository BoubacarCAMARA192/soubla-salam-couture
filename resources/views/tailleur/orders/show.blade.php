@extends('layouts.app')

@section('title', 'Atelier — '.$order->reference)
@section('page_title', 'Commande '.$order->reference)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
    <div>
        <h2 class="h4 font-decor text-bordeaux mb-0">{{ $order->reference }}</h2>
        <span class="badge fs-6" style="background-color: {{ \App\Models\Order::statusColor($order->status) ?? '#999' }};">{{ \App\Models\Order::statusLabel($order->status) }}</span>
        <span class="badge {{ $order->type === 'quote' ? 'bg-warning text-dark' : 'bg-or text-dark' }}">{{ $order->type === 'quote' ? 'devis' : 'commande' }}</span>
        @if ($order->delivery_date)
            · <span class="small {{ $order->is_late ? 'text-danger fw-bold' : '' }}">échéance {{ $order->delivery_date->format('d/m/Y') }} @if($order->is_late)(en retard)@endif</span>
        @endif
    </div>
    <a href="{{ route('tailleur.orders.index') }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-arrow-left"></i> Toutes les commandes</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        {{-- Informations détaillez (détails) --}}
        <div class="stat-card mb-3">
            <h3 class="h6 font-decor text-bordeaux">Détails de la demande</h3>
            <p class="mb-2 pt-2"><strong class="small">Client :</strong>
                <a class="text-bordeaux" href="{{ route('tailleur.clients.show', $order->client) }}">{{ $order->client->full_name }}</a>
                @if ($order->client->phone) · <small>{{ $order->client->phone }}</small> @endif
            </p>

            <div class="bg-creme rounded-3 p-3 mb-3">
                <i class="bi bi-chat-square-text text-or"></i> <strong>Description :</strong> {{ $order->description }}
                @if ($order->fabric) <div class="small mt-1"><strong>Tissu :</strong> {{ $order->fabric }}</div> @endif
                @if ($order->catalogModel) <div class="small"><strong>Modèle de base :</strong> « {{ $order->catalogModel->title }} »</div> @endif
                @if ($order->event_date) <div class="small"><strong>Événement :</strong> {{ $order->event_date->format('d/m/Y') }}</div> @endif
                @if ($order->inspiration_photo)
                    <img src="{{ asset('storage/'.$order->inspiration_photo) }}" class="rounded-3 mt-2" style="width: 110px; height: 110px; object-fit: cover;" alt="Inspiration">
                @endif
            </div>

            {{-- Mise à jour du devis / montant / échéance / affectation (TAI-04) --}}
            @include('partials.errors')
            <form method="POST" action="{{ route('tailleur.orders.update', $order) }}" class="row g-3 align-items-end">
                @csrf
                @method('PUT')
                <div class="col-6 col-md-3">
                    <label class="form-label small" for="amount">Montant (F)</label>
                    <input id="amount" name="amount" type="number" min="0" step="100" class="form-control"
                           value="{{ old('amount', $order->amount) }}" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small" for="delivery_date">Livraison</label>
                    <input id="delivery_date" name="delivery_date" type="date" class="form-control" value="{{ old('delivery_date', $order->delivery_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small" for="tailleur_id">Affecté à</label>
                    <select id="tailleur_id" name="tailleur_id" class="form-select">
                        <option value="">— ch. —</option>
                        @foreach ($tailleurs as $t)
                            <option value="{{ $t->id }}" @if($order->tailleur_id === $t->id) selected @endif>{{ $t->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-bordeaux"><i class="bi bi-save"></i> Enregistrer</button>
                </div>
                <div class="col-12">
                    <label class="form-label small" for="tailleur_notes">Note interne (invisible au client)</label>
                    <textarea id="tailleur_notes" name="tailleur_notes" rows="2" class="form-control">{{ old('tailleur_notes', $order->tailleur_notes) }}</textarea>
                </div>
            </form>
        </div>

        {{-- Faire avancer la commande : poste de travail (TAI-04) --}}
        @unless ($cancelled)
            <div class="section-title"><h3 class="h5"><i class="bi bi-kanban text-or"></i> Faire avancer la commande</h3></div>
            <div class="stat-card">
                @include('partials.errors')
                <form method="POST" action="{{ route('tailleur.orders.status', $order) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="status">Nouveau statut selon le circuit habituel</label>
                        <select id="status" name="status" required class="form-select">
                            <option value="">— Choisir le poste suivant —</option>
                            @foreach (\App\Models\Order::STATUSES as $key => $info)
                                <option value="{{ $key }}" @if($key === $order->status) disabled @endif>
                                    {{ $info[0] }} @if($key === $order->status)(actuel)@endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Les mesures doivent être prises avant la coupe. Marquer « Livrée » exige un solde de 0 (encaissement préalable ou annulé avec accord).</div>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small" for="comment">Justificatif / commentaire (256 max)</label>
                            <input id="comment" name="comment" class="form-control" placeholder="ex : essayage reporté au 12/01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small" for="photo">Photo de progression (facultatif)</label>
                            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-bordeaux mt-3"><i class="bi bi-arrow-right"></i> Ajouter ce poste à l'historique</button>
                </form>
            </div>

            @if ($order->status === \App\Models\Order::READY && $order->remaining_amount === 0.0)
                <div class="stat-card mt-2 bg-success bg-opacity-25 text-center py-2">
                    Commande prête et soldée — prête à être récupérée/spécifiée au client.
                </div>
            @elseif ($order->status === \App\Models\Order::READY)
                <div class="stat-card mt-2 bg-warning bg-opacity-25 text-center py-2">
                    Encaissez le solde de <strong>{{ number_format($order->remaining_amount, 0, ',', ' ') }} F</strong> ci-dessous avant livraison.
                </div>
            @endif
        @endunless
        @if ($cancelled)
            <div class="stat-card text-center bg-secondary bg-opacity-10 py-3">
                <i class="bi bi-slash-circle display-6"></i>
                <p class="mb-0">Commande annulée : fichiers et statuts verrouillés.</p>
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        {{-- Encaissement (TAI-06) --}}
        <div class="section-title"><h3 class="h6"><i class="bi bi-cash-stack text-or"></i> Encaissements</h3></div>
        <div class="stat-card mb-3">
            <div class="d-flex justify-content-between mb-1"><span>Total</span><strong>{{ number_format($order->amount, 0, ',', ' ') }} F</strong></div>
            <div class="d-flex justify-content-between mb-1 text-success"><span>Encaissé</span><span>{{ number_format($order->paid_amount, 0, ',', ' ') }} F</span></div>
            <div class="d-flex justify-content-between mb-2 {{ $order->remaining_amount > 0 ? 'text-danger' : 'text-success' }}"><span>Reste</span><span>{{ number_format($order->remaining_amount, 0, ',', ' ') }} F</span></div>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: {{ (int) min(100, $order->amount > 0 ? $order->paid_amount * 100 / $order->amount : 0) }}%"></div>
            </div>

            @unless ($cancelled)
                <form method="POST" action="{{ route('tailleur.payments.store', $order) }}" class="needs-validation row g-2 mt-2" novalidate>
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="col-6">
                        <input name="amount" type="number" min="100" step="100" @if ($order->amount > 0) max="{{ (int) $order->remaining_amount }}" @endif
                               class="form-control" placeholder="Montant" required value="{{ (int) $order->remaining_amount ?: '' }}">
                        <div class="invalid-feedback">Montant invalide (min 100 F).</div>
                    </div>
                    <div class="col-6">
                        <select name="method" class="form-select" required>
                            @foreach (\App\Models\Payment::METHODS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="type" class="form-select">
                            @foreach (\App\Models\Payment::TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 d-grid">
                        <button class="btn btn-bordeaux" @if ($order->amount > 0 && $order->remaining_amount <= 0) disabled @endif>
                            <i class="bi bi-plus-lg"></i> Encaisser
                        </button>
                    </div>
                </form>
            @endunless

            @if ($payments->count())
                <ul class="list-unstyled small mb-0 mt-3">
                    @foreach ($payments as $p)
                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span>{{ $p->paid_at->format('d/m/Y') }} — <strong>{{ number_format($p->amount, 0, ',', ' ') }} F</strong>
                                <span class="text-muted d-block">{{ $p->typeLabel() }} · {{ $p->methodLabel() }}</span>
                            </span>
                            <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('tailleur.receipt', $p) }}">Reçu</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Rendez-vous liés --}}
        <div class="section-title"><h3 class="h6"><i class="bi bi-calendar2-check text-or"></i> Rendez-vous liés</h3></div>
        <div class="stat-card mb-3">
            @if ($order->appointments->isEmpty())
                <p class="small text-muted mb-0">Aucun rendez-vous lié à cette commande.</p>
            @else
                @foreach ($order->appointments as $rdv)
                    <div class="d-flex justify-content-between small py-1">
                        <span>{{ $rdv->typeLabel() }} · {{ $rdv->status }}</span>
                        <span class="text-bordeaux">{{ \App\Helpers\Date::label($rdv->scheduled_at) }}</span>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Messagerie rapide (TAI-09) --}}
        <div class="section-title"><h3 class="h6"><i class="bi bi-chat-dots text-or"></i> Envoyer un message</h3></div>
        <div class="stat-card">
            <form method="POST" action="{{ route('tailleur.orders.message', $order) }}">
                @csrf
                <textarea name="body" rows="2" required class="form-control" placeholder="Message au client…"></textarea>
                <button class="btn btn-bordeaux w-100 mt-2"><i class="bi bi-send"></i> Envoyer</button>
            </form>
        </div>
    </div>
</div>

{{-- Historique pleine largeur + frise --}}
<div class="section-title"><h3 class="h5"><i class="bi bi-journals text-or"></i> Journal d'avancement ({{ $order->statusHistory->count() }})</h3></div>
<div class="stat-card">
    @include('partials.timeline', ['order' => $order])
    <div class="row g-2 mt-3">
        @foreach ($order->statusHistory as $h)
            <div class="col-12 py-1 border-bottom d-flex gap-2 flex-wrap small">
                <span class="dot mt-1" style="background-color: {{ \App\Models\Order::statusColor($h->status) ?? '#999' }}; width: 10px; height: 10px; border-radius: 50%; flex: 0 0 auto;"></span>
                <strong>{{ \App\Models\Order::statusLabel($h->status) }}</strong>
                <span class="text-muted">{{ $h->created_at->format('d/m/Y H:i') }} · {{ $h->changedBy?->full_name ?? 'chose' }}</span>
                @if ($h->comment)<span class="text-muted">— « {{ $h->comment }} »</span>@endif
                @if ($h->photo)
                    <a href="{{ asset('storage/'.$h->photo) }}" target="_blank" class="ms-auto">
                        <img src="{{ asset('storage/'.$h->photo) }}" class="rounded-2" style="width: 54px; height: 54px; object-fit: cover;" alt="Progression">
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

