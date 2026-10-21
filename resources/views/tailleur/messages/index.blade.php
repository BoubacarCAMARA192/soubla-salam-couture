@extends('layouts.app')

@section('title', 'Messagerie')
@section('page_title', 'Messagerie atelier')

@section('content')
@if ($clients->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="bi bi-chat-left-dots display-6 text-or"></i>
        <p class="mt-2 mb-0">Aucune conversation pour le moment.</p>
        <p class="small text-muted">Les questions des clients (depuis leurs commandes) s'afficheront ici.</p>
    </div>
@else
    <div class="list-group ssc-list">
        @foreach ($clients as $c)
            <a href="{{ route('tailleur.messages.show', $c) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-person-circle text-or me-1"></i> {{ $c->full_name }}
                    <span class="small text-muted">({{ $c->messages()->count() }} messages)</span>
                </span>
                @if ($c->unread_count > 0)
                    <span class="badge rounded-pill bg-bordeaux">{{ $c->unread_count }} non lu(s)</span>
                @endif
            </a>
        @endforeach
    </div>
    {{ $clients->links() }}
@endif
@endsection
