<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; }
    .title { background: #7b1e3a; color: #fff; padding: 8px 12px; text-align: center; }
    .title h1 { margin: 0; font-size: 16px; letter-spacing: 1px; }
    .title .sub { font-size: 10px; color: #fade89; }
    .recu { margin: 10px 0; text-align: center; font-size: 13px; font-weight: bold; color: #7b1e3a; }
    table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    td.pad { padding: 5px 8px; border-bottom: 1px solid #e4d7d7; }
    .k { color: #7b1e3a; font-weight: bold; width: 42%; }
    .box { border: 2px solid #7b1e3a; text-align: center; padding: 10px; margin: 12px 0; }
    .box .amount { font-size: 21px; font-weight: bold; color: #7b1e3a; }
    .amount-words { text-align: center; font-style: italic; font-size: 10px; color: #555; }
    footer { margin-top: 18px; border-top: 1px solid #999; padding-top: 6px; font-size: 9px; color: #666; text-align: center; }
    .sign { margin-top: 30px; width: 100%; }
    .sign td { font-size: 10px; }
</style>
</head>
<body>

<div class="title">
    <h1>{{ mb_strtoupper(\App\Models\Setting::get('atelier_name')) }}</h1>
    <div class="sub">{{ \App\Models\Setting::get('atelier_slogan') }} — Dakar, Sénégal</div>
</div>

<div class="recu">REÇU DE PAIEMENT N° {{ $payment->receipt_number }}</div>

<table>
    <tr><td class="k">Client</td><td class="pad">{{ $order->client->full_name }}{{ $order->client->phone ? ' ('.$order->client->phone.')' : '' }}</td></tr>
    <tr><td class="k">Commande</td><td class="pad">{{ $order->reference }} — {{ $order->type === 'quote' ? 'Devis' : 'Commande' }}</td></tr>
    @if ($order->catalogModel)
        <tr><td class="k">Modèle de base</td><td class="pad">{{ $order->catalogModel->title }}</td></tr>
    @endif
    <tr><td class="k">Type de paiement</td><td class="pad">{{ $payment->typeLabel() }}</td></tr>
    <tr><td class="k">Mode de paiement</td><td class="pad">{{ $payment->methodLabel() }}</td></tr>
    <tr><td class="k">Encaissé par</td><td class="pad">{{ $payment->recordedBy?->full_name ?? 'Atelier' }}</td></tr>
    <tr><td class="k">Date</td><td class="pad">Dakar, le {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td></tr>
</table>

<div class="box">
    MONTANT REÇU
    <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} F CFA</div>
</div>

<table>
    <tr><td class="k">Montant total commande</td><td class="pad">{{ number_format($order->amount, 0, ',', ' ') }} F CFA</td></tr>
    <tr><td class="k">Déjà versé (avec ce reçu)</td><td class="pad">{{ number_format($order->payments()->sum('amount'), 0, ',', ' ') }} F CFA</td></tr>
    <tr><td class="k">Solde restant</td><td class="pad">{{ number_format($order->remaining_amount, 0, ',', ' ') }} F CFA</td></tr>
</table>

<div class="sign">
    <table>
        <tr>
            <td style="border-bottom: 1px dotted #555; width: 44%; text-align: center; padding-top: 10px;">Cachet & signature de l'atelier</td>
            <td style="width: 8%;"></td>
            <td style="border-bottom: 1px dotted #555; width: 44%; text-align: center; padding-top: 10px;">Signature du client</td>
        </tr>
    </table>
</div>

<p class="amount-words">Merci de votre confiance.</p>

<footer>{{ \App\Models\Setting::get('receipt_footer', "Un acompte versé n'est pas remboursable si la commande est annulée après la coupe.") }}<br>
Tel. {{ \App\Models\Setting::get('atelier_phone') }} · {{ \App\Models\Setting::get('atelier_address') }}</footer>

</body>
</html>
