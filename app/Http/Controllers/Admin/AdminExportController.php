<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tailleur\ExportsCsv;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

/** Sauvegarde, restauration et export des données (ADM-07) */
class AdminExportController extends Controller
{
    public function index()
    {
        return view('admin.export');
    }

    public function download(string $type)
    {
        ActivityLog::record('export.csv', null, null, ['type' => $type]);

        switch ($type) {
            case 'clients':
                return \App\Http\Controllers\Tailleur\ExportsCsv::download(
                    ['ID', 'Nom complet', 'Téléphone', 'Adresse', 'Inscrit le', 'Nb commandes'],
                    Client::withCount('orders')->orderBy('full_name')->get()->map(fn ($c) => [
                        $c->id, $c->full_name, $c->phone, $c->address,
                        $c->created_at?->format('d/m/Y'), $c->orders_count,
                    ]),
                    'clients.csv'
                );

            case 'orders':
                return ExportsCsv::download(
                    ['Référence', 'Client', 'Tailleur', 'Type', 'Statut', 'Montant', 'Payé', 'Reste', 'Date prévue', 'Événement'],
                    Order::with(['client', 'tailleur'])->withSum('payments', 'amount')->orderBy('created_at')->get()->map(fn ($o) => [
                        $o->reference,
                        $o->client->full_name,
                        $o->tailleur?->full_name ?? '—',
                        $o->type === 'quote' ? 'Devis' : 'Commande',
                        Order::statusLabel($o->status),
                        $o->amount,
                        $o->payments_sum_amount ?? 0.0,
                        max(0, $o->amount - ($o->payments_sum_amount ?? 0)),
                        $o->delivery_date?->format('d/m/Y'),
                        $o->event_date?->format('d/m/Y'),
                    ]),
                    'commandes.csv'
                );

            case 'payments':
                return ExportsCsv::download(
                    ['Reçu', 'Commande', 'Client', 'Montant', 'Mode', 'Type', 'Payé le'],
                    Payment::with('order.client')->orderBy('paid_at')->get()->map(fn ($p) => [
                        $p->receipt_number,
                        $p->order->reference,
                        $p->order->client->full_name,
                        $p->amount,
                        $p->methodLabel(),
                        $p->typeLabel(),
                        $p->paid_at->format('d/m/Y'),
                    ]),
                    'paiements.csv'
                );

            case 'models':
                return ExportsCsv::download(
                    ['Titre', 'Catégorie', 'Type', 'Occasion', 'Prix indicatif', 'Statut', 'Vues', 'Commandes'],
                    CatalogModel::withCount('orders')->orderBy('title')->get()->map(fn ($m) => [
                        $m->title,
                        CatalogModel::CATEGORY_LABELS[$m->category] ?? $m->category,
                        $m->type,
                        $m->occasion,
                        $m->indicative_price,
                        $m->status,
                        $m->views_count,
                        $m->orders_count,
                    ]),
                    'catalogue.csv'
                );

            case 'expenses':
                return ExportsCsv::download(
                    ['Libellé', 'Catégorie', 'Montant', 'Dépense le'],
                    Expense::orderBy('spent_at')->get()->map(fn ($e) => [
                        $e->label, $e->category, $e->amount, $e->spent_at->format('d/m/Y'),
                    ]),
                    'depenses.csv'
                );

            case 'activity':
                return ExportsCsv::download(
                    ['Date', 'Utilisateur', 'Action', 'Détails'],
                    ActivityLog::with('user')->orderByDesc('created_at')->limit(2000)->get()->map(fn ($l) => [
                        $l->created_at->format('d/m/Y H:i'),
                        $l->user?->full_name ?? 'Système',
                        $l->action_label ?? $l->action,
                        $l->details,
                    ]),
                    'journal-activite.csv'
                );

            default:
                abort(404);
        }
    }

    /**
     * Sauvegarde de la base : téléchargement de la base SQLite active.
     * Sur MySQL, utiliser mysqldump (voir la documentation d'installation).
     */
    public function backup()
    {
        ActivityLog::record('export.csv', null, null, ['type' => 'sauvegarde']);

        if (config('database.default') !== 'sqlite') {
            return back()->with('error', 'Sauvegarde manuelle avec MySQL : utilisez la commande « mysqldump -u utilisateur -p soubla_salam_couture > sauvegarde.sql » (voir documentation).');
        }

        $path = database_path('database.sqlite');
        $filename = 'soubla-salam-couture-'.now()->format('Ymd-His').'.sqlite';

        return response()->download($path, $filename);
    }
}
