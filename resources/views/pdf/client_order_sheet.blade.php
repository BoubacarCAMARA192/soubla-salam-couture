<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 15px; color: #7b1e3a; }
    .band { background: #7b1e3a; color: #fff; padding: 6px 10px; font-size: 11px; }
    .band b { color: #fade89; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #f2e6ec; color: #7b1e3a; padding: 5px 7px; font-size: 10px; text-align: left; border-bottom: 2px solid #7b1e3a; }
    td { padding: 5px 7px; border-bottom: 1px solid #ddd; font-size: 10px; }
    .sec { font-weight: bold; color: #7b1e3a; margin-top: 14px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; }
    .hist td { padding: 3px 7px; }
</style>
</head>
<body>

<h1>SUIVI DE COMMANDE — {{ $order->reference }}</h1>
<div class="band">
    <b>Client :</b> {{ $order->client->full_name }}{{ $order->client->phone ? ' — '.$order->client->phone : '' }}<br>
    <b>Statut actuel :</b> {{ \App\Models\Order::statusLabel($order->status) }}
    <b class="ms-2">· Livraison prévue :</b> {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : 'à fixer' }}
</div>

<div class="sec">Description & tissu</div>
<table>
    <tr><td>{{ $order->description }}</td></tr>
    @if ($order->fabric)
        <tr><td><i style="color: #666;">Tissu fourn :</i> {{ $order->fabric }}</td></tr>
    @endif
</table>

<div class="sec">Historique de la confection</div>
<table class="hist">
    <thead>
        <tr><th>Date & heure</th><th>Poste / statut</th><th>Intervenant</th><th>Justificatif</th></tr>
    </thead>
    <tbody>
        @forelse ($order->statusHistory as $h)
            <tr>
                <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ \App\Models\Order::statusLabel($h->status) }}</td>
                <td>{{ $h->changedBy?->full_name ?? '—' }}</td>
                <td>{{ $h->comment ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">—</td></tr>
        @endforelse
    </tbody>
</table>

<div class="sec">Paiements</div>
<table class="hist">
    <thead>
        <tr><th>Reçu</th><th>Date</th><th>Type</th><th>Mode</th><th>Montant</th></tr>
    </thead>
    <tbody>
        @forelse ($order->payments as $p)
            <tr>
                <td>{{ $p->receipt_number }}</td>
                <td>{{ \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y') }}</td>
                <td>{{ $p->typeLabel() }}</td>
                <td>{{ $p->methodLabel() }}</td>
                <td>{{ number_format($p->amount, 0, ',', ' ') }} F</td>
            </tr>
        @empty
            <tr><td colspan="5">Aucun versement (total : {{ number_format($order->remaining_amount, 0, ',', ' ') }} F à régler).</td></tr>
        @endforelse
        <tr>
            <td colspan="4" style="text-align: right;"><b>Total encaissé</b></td>
            <td><b>{{ number_format($order->paid_amount, 0, ',', ' ') }} F / {{ number_format($order->amount, 0, ',', ' ') }} F</b></td>
        </tr>
    </tbody>
</table>

<div style="margin-top: 20px; font-size: 9px; color: #777; text-align: center;">
    Fiche remise au client pour suivi — {{ \App\Models\Setting::get('atelier_name') }} ·
    {{ \App\Models\Setting::get('atelier_phone') }}
</div>

</body>
</html>
