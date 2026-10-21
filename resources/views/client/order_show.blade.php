@extends('layouts.app')

@section('title', 'Suivi '.$order->reference)
@section('page_title', 'Suivi — '.$order->reference)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">

        {{-- État de la commande --}}
        <div class="stat-card mb-3">
            <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center mb-3">
                <div>
                    <h2 class="h5 font-decor text-bordeaux mb-0">{{ $order->reference }}</h2>
                    <span class="small text-muted">
                        {{ $order->type === 'quote' ? 'Demande de devis' : 'Commande' }} du {{ $order->created_at->format('d/m/Y') }}
                        @if ($order->delivery_date) — livraison prévue le {{ $order->delivery_date->format('d/m/Y') }} @endif
                    </span>
                </div>
                <span class="badge fs-6" style="background-color: {{ \App\Models\Order::statusColor($order->status) ?? '#888' }};">
                    {{ \App\Models\Order::statusLabel($order->status) }}
                </span>
            </div>

            <div class="mb-3">
                @include('partials.timeline', ['order' => $order])
            </div>

            <div class="bg-creme rounded-3 p-3">
                <i class="bi bi-chat-square-text text-or"></i> <strong>Description :</strong> {{ $order->description }}
                <div class="small text-muted mt-1">
                    @if ($order->catalogModel) Modèle : « {{ $order->catalogModel->title }} » · @endif
                    @if ($order->fabric) Tissu : {{ $order->fabric }} · @endif
                    @if ($order->event_date) Événement : {{ $order->event_date->format('d/m/Y') }} @endif
                </div>
                @if ($order->inspiration_photo)
                    <img src="{{ asset('storage/'.$order->inspiration_photo) }}" class="rounded-3 mt-2" style="width: 130px; height: 130px; object-fit: cover;" alt="Photo d'inspiration">
                @endif
            </div>

            @if ($order->tailleur_notes)
                <div class="wash-note mt-3">
                    <i class="bi bi-person-badge text-or"></i> <strong>Note de l'atelier :</strong> {{ $order->tailleur_notes }}
                </div>
            @endif
        </div>

        {{-- Historique des statuts (frise détaillée + photos de progression) --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-journals text-or"></i> Journal d'avancement</h2></div>

        @foreach ($order->statusHistory as $h)
            <div class="stat-card mb-2 py-2 px-3 d-flex gap-3 align-items-start">
                <span class="dot" style="background-color: {{ \App\Models\Order::statusColor($h->status) ?? '#999' }}; width: 12px; height: 12px; border-radius: 50%; margin-top: 6px; flex: 0 0 auto;"></span>
                <div class="flex-grow-1">
                    <strong>{{ \App\Models\Order::statusLabel($h->status) }}</strong>
                    <span class="small text-muted">— {{ $h->created_at->format('d/m/Y à H:i') }} par {{ $h->changedBy?->full_name ?? 'l\'atelier' }}</span>
                    @if ($h->comment) <div class="small">{{ $h->comment }}</div> @endif
                </div>
                @if ($h->photo)
                    <a href="{{ asset('storage/'.$h->photo) }}" target="_blank">
                        <img src="{{ asset('storage/'.$h->photo) }}" class="rounded-2" style="width: 64px; height: 64px; object-fit: cover;" alt="Progression">
                    </a>
                @endif
            </div>
        @endforeach

        {{-- Messagerie rattachée à la commande (CLI-08) --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-chat-dots text-or"></i> Échange avec l'atelier</h2></div>

        <div class="stat-card">
            @if ($order->messages->isEmpty())
                <p class="small text-muted">Aucun message pour l'instant. Posez votre question ici : l'atelier vous répond directement.</p>
            @else
                <div class="d-flex flex-column gap-2 mb-3">
                    @foreach ($order->messages as $msg)
                        <div class="d-flex flex-column {{ auth()->id() === $msg->sender_id ? 'align-items-end' : 'align-items-start' }}">
                            <div class="msg-bubble {{ auth()->id() === $msg->sender_id ? 'msg-me' : 'msg-them' }}">
                                {{ $msg->body }}
                                <div class="msg-meta text-end">{{ $msg->created_at->format('d/m H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('client.orders.message', $order) }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="body" required class="form-control" placeholder="Écrire un message à l'atelier…">
                    <button class="btn btn-bordeaux" type="submit"><i class="bi bi-send"></i></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Colonne : paiements et documents --}}
    <div class="col-lg-4">
        <div class="section-title"><h2 class="h6"><i class="bi bi-cash-coin text-or"></i> Paiements (CLI-09)</h2></div>

        <div class="stat-card mb-3">
            @if ((float) $order->amount <= 0)
                <p class="small text-muted mb-0">Le montant sera fixé par le devis de l'atelier.</p>
            @else
                <div class="d-flex justify-content-between mb-1"><span>Montant total</span><strong>{{ number_format($order->amount, 0, ',', ' ') }} F</strong></div>
                <div class="d-flex justify-content-between mb-1 text-success"><span>Déjà versé</span><span>{{ number_format($order->paid_amount, 0, ',', ' ') }} F</span></div>
                @if ($order->remaining_amount > 0)
                    <div class="d-flex justify-content-between text-danger mb-2"><span>Solde restant</span><span>{{ number_format($order->remaining_amount, 0, ',', ' ') }} F</span></div>
                @else
                    <div class="small text-success mb-2"><i class="bi bi-check-circle"></i> Commande intégralement réglée</div>
                @endif
                <div class="progress" role="progressbar" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ (int) min(100, $order->paid_amount * 100 / max(1, $order->amount)) }}%"></div>
                </div>
            @endif

            @if ($order->payments->count())
                <hr>
                <ul class="list-unstyled small mb-0">
                    @foreach ($order->payments as $p)
                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span>
                                {{ $p->paid_at->format('d/m/Y') }} — <strong>{{ number_format($p->amount, 0, ',', ' ') }} F</strong>
                                <span class="text-muted d-block">{{ $p->typeLabel() }} · {{ $p->methodLabel() }}</span>
                            </span>
                            <span class="small text-muted"><i class="bi bi-receipt"></i> {{ $p->receipt_number }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="small text-muted mt-2">Reçus : également disponibles dans la fiche PDF.</div>
            @endif
        </div>

        <div class="section-title"><h2 class="h6"><i class="bi bi-file-earmark-pdf text-or"></i> Documents</h2></div>
        <div class="d-grid gap-2">
            <a href="{{ route('client.orders.pdf', $order) }}" class="btn btn-outline-bordeaux rounded-pill">
                <i class="bi bi-file-pdf"></i> Fiche complète (PDF)
            </a>
        </div>

        {{-- Évaluation après livraison (CLI-10) --}}
        @if ($order->status === \App\Models\Order::DELIVERED)
            <div class="section-title"><h2 class="h6"><i class="bi bi-star text-or"></i> Votre évaluation</h2></div>
            <div class="stat-card">
                @include('partials.flash')
                @include('partials.errors')
                @if ($order->reviews->count())
                    <p class="small mb-0">Merci ! Votre avis « <em>{{ $order->reviews->first()->status === 'published' ? 'publié' : 'en attente de validation' }}</em> ».</p>
                @else
                    <form method="POST" action="{{ route('client.orders.review', $order) }}">
                        @csrf
                        <div class="mb-2 d-flex gap-2 justify-content-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-check">
                                    <input class="btn-check" type="radio" name="rating" id="r{{ $i }}" value="{{ $i }}" @if($i === 5) checked @endif>
                                    <label class="btn btn-outline-bordeaux p-2" for="r{{ $i }}">{{ $i }} <i class="bi bi-star"></i></label>
                                </div>
                            @endfor
                        </div>
                        <textarea name="comment" rows="2" class="form-control" placeholder="Optionnel : un mot sur la coupe, la finition, la ponctualité…"></textarea>
                        <button class="btn btn-bordeaux w-100 mt-2 rounded-pill"><i class="bi bi-star"></i> Noter la prestation</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
