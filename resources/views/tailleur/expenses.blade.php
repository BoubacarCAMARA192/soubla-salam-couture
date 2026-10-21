@extends('layouts.app')

@section('title', 'Dépenses')
@section('page_title', 'Dépenses de l\'atelier (TAI-10)')

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="d-flex flex-wrap gap-2">
        @foreach (range(1, 12) as $m)
            <a class="btn btn-sm {{ $month === $m ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill"
               href="{{ route('tailleur.expenses.index', ['month' => $m]) }}">{{ \Carbon\Carbon::create(now()->year, $m, 1)->translatedFormat('M') }}</a>
        @endforeach
    </div>
    <div class="stat-card py-2 px-3 ms-auto text-center">
        <span class="small text-muted d-block">Total {{ \App\Helpers\Date::monthLabel($month, now()->year) }}</span>
        <strong class="fs-5 text-bordeaux">{{ number_format($totalMonth, 0, ',', ' ') }} F</strong>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="section-title"><h2 class="h5"><i class="bi bi-receipt-cutoff text-or"></i> Charges du mois</h2></div>

        @include('partials.flash')

        @if ($expenses->isEmpty())
            <div class="stat-card text-center py-4 small text-muted">Aucune dépense sur ce mois.</div>
        @else
            <div class="table-responsive table-cards">
                <table class="table align-middle ssc-table">
                    <thead><tr><th>Date</th><th>Libellé</th><th>Catégorie</th><th>Montant</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($expenses as $e)
                            <tr>
                                <td>{{ $e->spent_at->format('d/m/Y') }}</td>
                                <td>{{ $e->label }}</td>
                                <td>@if ($e->category) <span class="badge text-bg-light border">{{ $e->category }}</span> @else — @endif</td>
                                <td class="nowrap">{{ number_format($e->amount, 0, ',', ' ') }} F</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('tailleur.expenses.destroy', $e) }}" onsubmit="return confirm('Supprimer cette dépense ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $expenses->links() }}
        @endif
    </div>

    <div class="col-lg-4">
        <div class="section-title"><h2 class="h5"><i class="bi bi-plus-circle text-or"></i> Nouvelle dépense</h2></div>
        <div class="stat-card">
            @include('partials.errors')
            <form method="POST" action="{{ route('tailleur.expenses.store') }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label small" for="label">Libellé <span class="text-danger">*</span></label>
                    <input id="label" name="label" class="form-control" value="{{ old('label') }}" placeholder="ex : rouleaux de fil" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small" for="category">Catégorie</label>
                    <input id="category" name="category" list="catList" class="form-control" value="{{ old('category') }}">
                    <datalist id="catList">
                        @foreach (array_merge(['tissus', 'fournitures', 'transport', 'électricité', 'salaires', 'publicité'], $categories) as $c)
                            <option value="{{ $c }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small" for="amount">Montant <span class="text-danger">*</span></label>
                        <input id="amount" name="amount" type="number" min="1" step="50" class="form-control" value="{{ old('amount') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small" for="spent_at">Date <span class="text-danger">*</span></label>
                        <input id="spent_at" name="spent_at" type="date" class="form-control" value="{{ old('spent_at', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <textarea name="notes" rows="2" class="form-control" placeholder="Notes" maxlength="500">{{ old('notes') }}</textarea>
                <button class="btn btn-bordeaux w-100 mt-3"><i class="bi bi-check2"></i> Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection
