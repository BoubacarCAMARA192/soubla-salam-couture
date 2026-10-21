<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; color: #7b1e3a; border-bottom: 3px solid #7b1e3a; padding-bottom: 6px; }
    .meta { color: #666; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #7b1e3a; color: #fff; padding: 5px 7px; font-size: 10px; text-align: left; }
    td { padding: 5px 7px; border-bottom: 1px solid #ddd; font-size: 10px; }
    .type-title { margin-top: 14px; font-weight: bold; color: #7b1e3a; font-size: 12px; }
</style>
</head>
<body>

<h1>FICHE DE MENSURATIONS — {{ mb_strtoupper($client->full_name) }}</h1>
<div class="meta">
    {{ \App\Models\Setting::get('atelier_name') }} · Généré le {{ now()->format('d/m/Y à H\hi') }}<br>
    Téléphone {{ $client->phone ?? '—' }} · {{ $client->measurements()->count() }} prise(s) enregistrée(s)
</div>

@forelse ($latest as $type => $records)
    <div class="type-title">{{ $type }}</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>POITRINE</th><th>TAILLE</th><th>HANCHES</th><th>ÉPAULES</th>
                <th>MANCHE</th><th>LONG.CORPS</th><th>LONG.PANT.</th><th>CUISSE</th>
                <th>Prises par</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records->sortBy('created_at') as $m)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $m->chest ?? '—' }}</td>
                    <td>{{ $m->waist ?? '—' }}</td>
                    <td>{{ $m->hips ?? '—' }}</td>
                    <td>{{ $m->shoulders ?? '—' }}</td>
                    <td>{{ $m->sleeve_length ?? '—' }}</td>
                    <td>{{ $m->top_length ?? '—' }}</td>
                    <td>{{ $m->pants_length ?? '—' }}</td>
                    <td>{{ $m->thigh ?? '—' }}</td>
                    <td>{{ $m->takenBy?->full_name ?? '—' }}</td>
                </tr>
                @if ($m->notes)
                    <tr><td colspan="10" style="font-style: italic; color: #666;">Notes : {{ $m->notes }}</td></tr>
                @endif
                @if ($m->extras)
                    @php $extras = $m->extras; @endphp
                    <tr><td colspan="10" style="color: #555;">Mesures personnalisées :
                        @foreach ($extras as $ex)
                            {{ $ex['label'] }} = {{ $ex['value'] }}{{ $loop->last ? '' : ' · ' }}
                        @endforeach
                    </td></tr>
                @endif
            @endforeach
        </tbody>
    </table>
@empty
    <p style="margin-top: 20px;">Aucune mensuration enregistrée pour ce client.</p>
@endforelse

<div class="meta" style="margin-top: 26px;">
    Unités : cm. Les mensurations du corps évoluent : refaites une prise à chaque nouvelle commande.
</div>

</body>
</html>
