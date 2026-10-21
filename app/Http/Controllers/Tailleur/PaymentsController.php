<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\ShopNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /** Enregistrement manuel d'un paiement (TAI-06, TRA-04) */
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:100'],
            'method' => ['required', 'in:'.implode(',', array_keys(Payment::METHODS))],
            'type' => ['required', 'in:'.implode(',', array_keys(Payment::TYPES))],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'amount.min' => 'Le montant minimum d\'un paiement est de 100 FCFA.',
            'method.in' => 'Choisissez un mode de paiement valide.',
            'paid_at.before_or_equal' => 'La date de paiement ne peut pas être future.',
        ]);

        if (in_array($order->status, [Order::REQUESTED])) {
            return back()->with('error', "Le paiement se prend après la validation de la commande (devis accepté, acompte convenu).");
        }

        if ((float) $data['amount'] > $order->remaining_amount) {
            return back()->with('error', 'Le montant dépasse le solde restant '.$order->remaining_amount.' FCFA.');
        }

        $payment = $order->payments()->create([
            'receipt_number' => Payment::generateReceiptNumber(),
            'amount' => $data['amount'],
            'method' => $data['method'],
            'type' => $data['type'],
            'paid_at' => $data['paid_at'],
            'recorded_by' => $request->user()->id,
        ]);

        \App\Models\ActivityLog::record('payment.create', 'payment', $payment->id, [
            'reference' => $payment->receipt_number,
            'montant' => $data['amount'],
        ]);

        $order->client->user?->notify(new ShopNotification(
            title: 'Paiement de '.number_format($payment->amount, 0, ',', ' ').' FCFA enregistré',
            body: 'Votre '.$payment->typeLabel().' de '.$payment->amount.' FCFA (réf. '.$payment->receipt_number.') a été enregistré sur la commande '.$order->reference.'.',
            url: route('client.orders.show', $order),
            icon: 'cash-coin',
        ));

        return redirect()->route('tailleur.receipt', $payment)
            ->with('success', 'Paiement de '.$payment->amount.' FCFA enregistré. Le reçu prêt à être imprimé vient de s\'ouvrir.');
    }

    /** Document PDF reçu (TRA-03) */
    public function receipt(Payment $payment)
    {
        $payment->load(['order.client', 'order.catalogModel', 'recordedBy']);

        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'order' => $payment->order,
            'atelier' => \App\Models\Setting::defaults() + ['get' => fn () => ''],
        ])
            ->setPaper('a5', 'portrait');

        return $pdf->stream('recu-'.$payment->receipt_number.'.pdf');
    }

    /** Facture PDF : récapitulatif encaissements / solde */
    public function invoice(Order $order)
    {
        $order->load(['client', 'catalogModel', 'payments']);

        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order])->setPaper('a4', 'portrait');

        return $pdf->stream('facture-'.$order->reference.'.pdf');
    }
}
