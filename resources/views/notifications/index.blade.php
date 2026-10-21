@extends('layouts.app')

@section('title', 'Notifications')
@section('page_title', 'Mes notifications')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h2 class="h6 mb-0"><i class="bi bi-bell text-or"></i> Centre de suivi</h2>
    @if ($notifications->count())
        <form method="POST" action="{{ route('notifications.read_all') }}">
            @csrf
            <button class="btn btn-sm btn-outline-bordeaux rounded-pill"><i class="bi bi-check2-all"></i> Tout marquer comme lu</button>
        </form>
    @endif
</div>

@if ($notifications->isEmpty())
    <div class="stat-card text-center py-5 text-muted small">
        <i class="bi bi-bell-slash display-6 d-block mb-2 text-muted"></i>
        Aucune notification pour le moment. Les changements de statut, encassements et rendez-vous arrivent ici.
    </div>
@else
    <div class="row g-2">
        @foreach ($notifications as $n)
            <div class="col-12">
                <div class="stat-card {{ $n->read_at ? '' : 'border-start border-warning border-4' }} d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <strong>{{ $n->data['title'] ?? '' }}</strong>
                        @unless ($n->read_at)
                            <span class="badge bg-warning text-dark ms-1">nouveau</span>
                        @endunless
                        <p class="small text-muted mb-1 mt-1">{{ $n->data['body'] ?? '' }}</p>
                        <span class="small text-muted">{{ \App\Helpers\Date::label($n->created_at) }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        @if (!empty($n->data['url']))
                            <a class="btn btn-sm btn-bordeaux rounded-pill" href="{{ $n->data['url'] }}">
                                @php($icon = $n->data['icon'] ?? 'eye')
                                <i class="bi bi-{{ preg_match('/^[\w-]+$/', $icon) ? $icon : 'eye' }}"></i> Voir
                            </a>
                        @endif
                        @unless ($n->read_at)
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-bordeaux" title="Marquer comme lu"><i class="bi bi-check2"></i></button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $notifications->links() }}
@endif
@endsection
