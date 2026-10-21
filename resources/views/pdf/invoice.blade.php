<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #222; }
    .header { display: block; border-bottom: 3px solid #7b1e3a; padding-bottom: 8px; }
    .brand { font-size: 20px; font-weight: bold; color: #7b1e3a; }
    .brand small { display: block; font-size: 11px; color: #555; font-weight: normal; }
    h1.doc { font-size: 15px; margin: 12px 0 2px; color: #7b1e3a; }
    .meta { font-size: 10px; color: #666; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #7b1e3a; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
    td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
    .right { text-align: right; }
    .totals td { border: none; padding: 3px 8px; font-size: 12px; }
    .totals .label { color: #555; }
    .totals .due { font-size: 15px; font-weight: bold; color: #7b1e3a; }
    .footer { margin-top: 24px; font-size: 9px; color: #777; text-align: center; border-top: 1px solid #bbb; padding-top: 6px; }
</style>
</head>
<body>

<div class="header">
    <span class="brand">{{ mb_strtoupper(\App\Models\Setting::get('atelier_name')) }}
        <small>{{ \App\Models\Setting::get('atelier_address') }} · Tel {{ \App\Models\Setting::get('atelier_phone') }} · {{ \App\Models\Setting::get('atelier_email') }}</small>
    </span>
</div>

<h1 class="doc">FACTURE / RÉCAPITULATIF — {{ $order->reference }}</h1>
<div class="meta">
    Client : {{ $order->client->full_name }}{{ $order->client->phone ? ' — '.$order->client->phone : '' }}<br>
    Date de commande : {{ $order->created_at->format('d/m/Y') }}
    @if ($order->delivery_date) · Livraison prévue : {{ $order->delivery_date->format('d/m/Y') }} @endif
    @if ($order->event_date) · Événement : {{ $order->event_date->format('d/m/Y') }} @endif
</div>

@if ($order->description)
    <div class="meta" style="margin-top: 6px;">
        <em>Objet :</em> {{ $order->description }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Désignation</th>
            <th class="right" style="width: 22%;">Montant (F CFA)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{ $order->type === 'quote' ? 'DEVIS' : 'CONFECTION' }} de tenue sur mesure
                @if ($order->catalogModel)<br><span style="color: #777;">modèle de base : « {{ $order->catalogModel->title }} »</span>@endif
                @if ($order->fabric)<br><span style="color: #777;">tissu : {{ $order->fabric }}</span>@endif
            </td>
            <td class="right">{{ number_format($order->amount, 0, ',', ' ') }}</td>
        </tr>
    </tbody>
</table>

<table class="totals" style="margin-top: 14px;">
    <tr><td class="label">Montant total</td><td class="right">{{ number_format($order->amount, 0, ',', ' ') }} F CFA</td></tr>
    <tr><td class="label">Encaissé ({{ $order->payments->count() }} versement(s))</td><td class="right">{{ number_format($order->paid_amount, 0, ',', ' ') }} F CFA</td></tr>
    <tr>
        <td class="label {{ $order->remaining_amount > 0 ? '' : 'due-ok' }}">Solde à régler</td>
        <td class="right due">{{ number_format($order->remaining_amount, 0, ',', ' ') }} F CFA</td>
    </tr>
</table>

@if ($order->payments->count())
    <table style="margin-top: 16px;">
        <thead>
            <tr><th>Reçu n°</th><th>Date</th><th>Type</th><th>Mode</th><th class="right" style="width: 20%;">Montant</th></tr>
        </thead>
        <tbody>
            @foreach ($order->payments as $p)
                <tr>
                    <td>{{ $p->receipt_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y') }}</td>
                    <td>{{ $p->typeLabel() }}</td>
                    <td>{{ $p->methodLabel() }}</td>
                    <td class="right">{{ number_format($p->amount, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="footer">
    {{ \App\Models\Setting::get('receipt_footer', 'Documents valables pour toute réclamation sous 7 jours.') }}<br>
    {{ \App\Models\Setting::get('atelier_name') }} — Dakar · Wave {{ \App\Models\Setting::get('payment_wave_number', '—') }} ·
    OM {{ \App\Models\Setting::get('payment_om_number', '—') }}
</div>

</body>
</html>
