@extends('layouts.app')

@section('title', 'Paramètres')
@section('page_title', 'Paramètres de la plateforme (ADM-05)')

@section('content')
@include('partials.flash')

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="row g-4">
        @foreach ($groups as $groupName => $keys)
            <div class="col-lg-6">
                <div class="stat-card h-100 p-4">
                    <h2 class="h6 font-decor text-bordeaux">{{ $groupName }}</h2>
                    @foreach ($keys as $key => $label)
                        @php
                            $current = $settings[$key]->value ?? \App\Models\Setting::defaults()[$key] ?? '';
                            $isLong = in_array($key, ['atelier_hours', 'about_text', 'receipt_footer']);
                        @endphp
                        <div class="mb-3">
                            <label class="form-label small" for="{{ $key }}">{{ $label }} <span class="text-muted">({{ $key }})</span></label>
                            @if ($isLong)
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="3" class="form-control">{{ $current }}</textarea>
                            @else
                                <input id="{{ $key }}" name="{{ $key }}" type="{{ str_contains($key, 'url') ? 'url' : 'text' }}" class="form-control" value="{{ $current }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Grilles de mensurations paramétrables (ADM-05) --}}
        <div class="col-lg-6">
            <div class="stat-card h-100 p-4">
                <h2 class="h6 font-decor text-bordeaux">Types de vêtements (grilles de mesures)</h2>
                <p class="small text-muted">Un type actif apparaît dans les grilles de mensurations. Décochez pour le masquer sans perdre l'historique.</p>
                <table class="table table-sm mb-3">
                    @foreach ($clothingTypes as $ct)
                        <tr>
                            <td class="small">{{ $ct->name }}</td>
                            <td class="text-end">
                                <input type="hidden" name="clothing_type_id[{{ $ct->id }}][state]" value="0">
                                <input type="checkbox" name="clothing_type_id[{{ $ct->id }}][state]" value="1" @checked($ct->is_active) class="form-check-input">
                            </td>
                        </tr>
                    @endforeach
                </table>
                <label class="form-label small" for="new_clothing_type">Ajouter des types (séparés par virgule ou retour ligne)</label>
                <input id="new_clothing_type" name="new_clothing_type" class="form-control" placeholder="ex : ensemble, djellaba, dupatta">
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-save"></i> Enregistrer les paramètres</button>
    </div>
</form>
@endsection
