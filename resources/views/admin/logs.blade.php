@extends('layouts.app')

@section('title', 'Journal d\'activité')
@section('page_title', 'Journal d\'activité (ADM-06)')

@section('content')
<div class="d-flex flex-wrap gap-2 align-items-end mb-3">
    <form method="GET" action="{{ route('admin.logs') }}" class="d-flex gap-2 flex-wrap">
        <select name="user" class="form-select" style="width: 220px;">
            <option value="">Tous les utilisateurs</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @if($userFilter == $u->id) selected @endif>{{ $u->full_name }}</option>
            @endforeach
        </select>
        <select name="action" class="form-select" style="width: 220px;">
            <option value="">Toutes les actions</option>
            @foreach (\App\Models\ActivityLog::ACTION_LABELS as $key => $label)
                <option value="{{ $key }}" @if($actionFilter === $key) selected @endif>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-bordeaux"><i class="bi bi-funnel"></i> Filtrer</button>
        <a class="btn btn-outline-bordeaux" href="{{ route('admin.logs') }}">Tout l'historique</a>
    </form>
</div>

@if ($logs->isEmpty())
    <div class="stat-card text-center py-5 small text-muted">Aucun événement avec ces filtres.</div>
@else
    <div class="stat-card p-0 py-1">
        @foreach ($logs as $log)
            <div class="d-flex justify-content-between align-items-start py-2 px-3 flex-wrap gap-1 log-row {{ $loop->odd ? 'bg-creme' : '' }}">
                <span class="flex-grow-1">
                    <strong class="text-bordeaux">{{ $log->user?->full_name ?? 'système' }}</strong>
                    — {{ $log->action_label ?? $log->action }}
                    @if ($log->details)
                        <span class="small text-muted d-block">
                            <i class="bi bi-info-circle"></i>
                            @php $details = json_decode($log->details, true); @endphp
                            @if (is_array($details))
                                {{ collect($details)->map(fn ($v, $k) => $k.' : '.(is_bool($v) ? ($v ? 'oui' : 'non') : $v))->implode(' · ') }}
                            @else
                                {{ $log->details }}
                            @endif
                        </span>
                    @endif
                </span>
                <span class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</span>
            </div>
        @endforeach
    </div>
    {{ $logs->links() }}
@endif
@endsection
