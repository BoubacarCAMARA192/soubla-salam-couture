@extends('layouts.app')

@section('title', 'Modération des avis')
@section('page_title', 'Avis clients (CAT-10)')

@section('content')
@include('partials.flash')

@if ($reviews->isEmpty())
    <div class="stat-card text-center py-5 small text-muted">Aucun avis déposé pour l'instant.</div>
@else
    @foreach ($reviews as $r)
        <div class="stat-card mb-2">
            <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <strong>{{ $r->user?->full_name ?? 'Client' }}</strong>
                        <span class="text-or">@for ($i = 1; $i <= 5; $i++) <i class="bi {{ $i <= $r->rating ? 'bi-star-fill' : 'bi-star' }}"></i> @endfor</span>
                        @if ($r->order) <span class="badge text-bg-light border">{{ $r->order->reference }}</span> @endif
                    </div>
                    <p class="mb-0 mt-1">{{ $r->comment ?? '—' }}</p>
                    <span class="small text-muted">sur {{ $r->catalogModel?->title ?? '—' }} · {{ $r->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end">
                    <span class="badge {{ $r->status === 'pending' ? 'bg-warning text-dark' : ($r->status === 'published' ? 'bg-success' : 'bg-secondary') }}">
                        {{ $r->statusLabel() }}
                    </span>
                    <div class="d-flex gap-1">
                        @if ($r->status !== 'published')
                            <form method="POST" action="{{ route('admin.reviews.moderate', $r) }}">
                                @csrf
                                <input type="hidden" name="status" value="published">
                                <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Publier</button>
                            </form>
                        @endif
                        @if ($r->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.reviews.moderate', $r) }}">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i> Rejeter</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Supprimer définitivement cet avis ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{ $reviews->links() }}
@endif
@endsection
