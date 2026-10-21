@extends('layouts.app')

@section('title', 'Messagerie — '.$client->full_name)
@section('page_title', 'Conversation — '.$client->full_name)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('tailleur.messages.index') }}" class="btn btn-sm btn-outline-bordeaux"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h2 class="h5 font-decor text-bordeaux mb-0">{{ $client->full_name }}</h2>
        <span class="small text-muted">{{ $client->phone ?? '' }}</span>
    </div>
    <a href="{{ route('tailleur.clients.show', $client) }}" class="btn btn-sm btn-outline-bordeaux ms-auto"><i class="bi bi-person-vcard"></i> Fiche client</a>
</div>

<div class="stat-card">
    @include('partials.flash')

    @if ($conversation->isEmpty())
        <p class="small text-muted">Conversation vide.</p>
    @else
        <div class="d-flex flex-column gap-2 mb-3" style="max-height: 55vh; overflow-y: auto;" data-auto-scroll>
            @foreach ($conversation as $msg)
                <div class="d-flex flex-column {{ $msg->sender->role === 'client' ? 'align-items-start' : 'align-items-end' }}">
                    <small class="text-muted mb-1">
                        {{ $msg->sender->full_name ?? '—' }} · {{ $msg->created_at->format('d/m/Y H:i') }}
                        @if ($msg->order) · <a class="text-bordeaux" href="{{ route('tailleur.orders.show', $msg->order) }}">{{ $msg->order->reference }}</a> @endif
                    </small>
                    <div class="msg-bubble {{ $msg->sender->role === 'client' ? 'msg-them' : 'msg-me' }}">
                        {{ $msg->body }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('tailleur.messages.reply', $client) }}">
        @csrf
        <div class="input-group">
            <input type="text" name="body" required class="form-control" maxlength="2000" placeholder="Répondre au client…">
            <button class="btn btn-bordeaux" type="submit"><i class="bi bi-send"></i> Répondre</button>
        </div>
        <div class="form-text">Le client reçoit une notification dans son espace client.</div>
    </form>
</div>
@endsection
