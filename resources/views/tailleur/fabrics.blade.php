@extends('layouts.app')

@section('title', 'Stock tissus')
@section('page_title', 'Stock tissus (TAI-08)')

@section('content')
@if ($lowFabrics->count())
    <div class="alert alert-warning d-flex flex-wrap align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>{{ $lowFabrics->count() }} tissu(s) sous le seuil :</strong>
            {{ $lowFabrics->map(fn ($f) => $f->name.' ('.number_format($f->quantity, 1, ',', ' ').' '.$f->unit.')')->implode(', ') }}.
        </div>
        <a href="{{ route('tailleur.fabrics.alerts') }}" class="btn btn-sm btn-outline-bordeaux ms-auto">
            <i class="bi bi-download"></i> Export CSV des alertes
        </a>
    </div>
@else
    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('tailleur.fabrics.alerts') }}" class="btn btn-sm btn-outline-bordeaux">
            <i class="bi bi-download"></i> Export CSV
        </a>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="section-title"><h2 class="h5"><i class="bi bi-boxes text-or"></i> Inventaire ({{ $fabrics->count() }} références)</h2></div>

        @include('partials.flash')
        @include('partials.errors')

        @foreach ($fabrics as $f)
            <div class="stat-card py-2 px-3 mb-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span>
                        <strong>{{ $f->name }}</strong>
                        @if ($f->color) <span class="text-muted">· {{ $f->color }}</span> @endif
                        @if ($f->is_low) <span class="badge bg-warning text-dark ms-1"><i class="bi bi-exclamation-triangle"></i> stock bas</span> @endif
                    </span>
                    <span class="d-flex gap-2 align-items-center">
                        <span class="fw-bold {{ $f->is_low ? 'text-danger' : '' }}">{{ number_format($f->quantity, 1, ',', ' ') }} {{ $f->unit }}</span>
                        <button class="btn btn-sm btn-outline-bordeaux py-0" data-bs-toggle="collapse" data-bs-target="#stock-{{ $f->id }}"><i class="bi bi-arrows-expand"></i> Mouvement</button>
                        <form method="POST" action="{{ route('tailleur.fabrics.destroy', $f) }}" onsubmit="return confirm('Retirer « {{ $f->name }} » du stock ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </span>
                </div>
                <div class="small text-muted">Seuil d'alerte : {{ number_format($f->alert_threshold, 1, ',', ' ') }} {{ $f->unit }}</div>

                {{-- Formulaire de mouvement (entrée / sortie) --}}
                <div class="collapse" id="stock-{{ $f->id }}">
                    <form method="POST" action="{{ route('tailleur.fabrics.movement', $f) }}" class="row g-2 mt-1">
                        @csrf
                        <div class="col-auto">
                            <select name="type" class="form-select form-select-sm">
                                <option value="in">Entrée (+)</option>
                                <option value="out">Sortie (−)</option>
                            </select>
                        </div>
                        <div class="col-4 col-md-3">
                            <input name="quantity" type="number" min="0.5" step="0.5" required class="form-control form-control-sm" placeholder="Qté">
                        </div>
                        <div class="col-md-4">
                            <input name="order_reference" class="form-control form-control-sm" placeholder="réf commande si sortie (ex : SSC-26-00001)">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-bordeaux"><i class="bi bi-check2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        {{-- Modification rapide des seuils --}}
        <div class="section-title"><h2 class="h5"><i class="bi bi-sliders text-or"></i> Ajuster les seuils</h2></div>
        <div class="stat-card">
            <table class="table table-sm mb-0 ssc-table">
                @foreach ($fabrics as $f)
                    <tr>
                        <td class="small">{{ $f->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('tailleur.fabrics.update', $f) }}" class="d-flex gap-2 justify-content-end">
                                @csrf
                                <input type="hidden" name="name" value="{{ $f->name }}">
                                <input type="hidden" name="quantity" value="{{ $f->quantity }}">
                                <input type="hidden" name="unit" value="{{ $f->unit }}">
                                <input type="hidden" name="notes" value="{{ $f->notes }}">
                                <input type="hidden" name="color" value="{{ $f->color }}">
                                <input name="alert_threshold" type="number" min="0" step="1" class="form-control form-control-sm" style="width: 100px;" value="{{ $f->alert_threshold }}">
                                <button class="btn btn-sm btn-outline-bordeaux py-0"><i class="bi bi-check"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="section-title"><h2 class="h5"><i class="bi bi-plus-circle text-or"></i> Nouveau tissu</h2></div>
        <div class="stat-card">
            @include('partials.errors')
            <form method="POST" action="{{ route('tailleur.fabrics.store') }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label small" for="name">Nom <span class="text-danger">*</span></label>
                    <input id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="ex : Bazin getzner bleu" required>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-7">
                        <label class="form-label small" for="color">Couleur</label>
                        <input id="color" name="color" class="form-control" value="{{ old('color') }}" placeholder="ex : bleu marine">
                    </div>
                    <div class="col-5">
                        <label class="form-label small" for="unit">Unité <span class="text-danger">*</span></label>
                        <select id="unit" name="unit" required class="form-select">
                            <option value="m">mètres</option>
                            <option value="m²">mètres carrés</option>
                            <option value="pce">pièces</option>
                        </select>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small" for="quantity">Quantité initiale <span class="text-danger">*</span></label>
                        <input id="quantity" name="quantity" type="number" min="0" step="0.5" required class="form-control" value="{{ old('quantity', 0) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label small" for="alert_threshold">Seuil d'alerte <span class="text-danger">*</span></label>
                        <input id="alert_threshold" name="alert_threshold" type="number" min="0" step="0.5" required class="form-control" value="{{ old('alert_threshold', 5) }}">
                    </div>
                </div>
                <textarea name="notes" rows="2" class="form-control" placeholder="Notes (fournisseur, prix…)" maxlength="500">{{ old('notes') }}</textarea>
                <button class="btn btn-bordeaux w-100 mt-3"><i class="bi bi-plus-lg"></i> Ajouter au stock</button>
            </form>
        </div>

        <div class="section-title"><h2 class="h5"><i class="bi bi-activity text-or"></i> Derniers mouvements</h2></div>
        <div class="stat-card">
            @if ($movements->isEmpty())
                <p class="small text-muted mb-0">Aucun mouvement enregistré.</p>
            @else
                @foreach ($movements as $mv)
                    <div class="d-flex justify-content-between align-items-center small py-1 border-bottom">
                        <span>
                            <span class="badge {{ $mv->type === 'in' ? 'bg-success' : 'bg-danger' }}">{{ $mv->type === 'in' ? '+' : '−' }}</span>
                            {{ $mv->fabric?->name ?? '—' }} · {{ number_format($mv->quantity, 1, ',', ' ') }}
                        </span>
                        <span class="text-muted text-end">
                            {{ $mv->created_at->format('d/m') }}<br>
                            {{ \Illuminate\Support\Str::limit((string) $mv->reason, 28) }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
