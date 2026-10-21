<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Models\FabricMovement;
use App\Models\Order;
use Illuminate\Http\Request;

class FabricsController extends Controller
{
    public function index()
    {
        $fabrics = Fabric::orderBy('name')->get();
        $lowFabrics = $fabrics->filter(fn ($f) => $f->is_low);
        $movements = FabricMovement::with(['fabric', 'order', 'user'])->orderBy('created_at', 'desc')->take(20)->get();

        return view('tailleur.fabrics', [
            'fabrics' => $fabrics,
            'lowFabrics' => $lowFabrics,
            'movements' => $movements,
            'fabric' => new Fabric,
        ]);
    }

    /** Export CSV des alertes de stock (ADM-07, TAI-08) */
    public function alerts()
    {
        $list = Fabric::whereColumn('quantity', '<=', 'alert_threshold')->orderBy('name')->get();

        $rows = $list->map(fn ($f) => [
            $f->name,
            $f->color,
            $f->quantity.' '.$f->unit,
            $f->alert_threshold.' '.$f->unit,
        ]);

        if ($rows->isEmpty()) {
            $rows = collect([['—', '—', '—', 'Aucune alerte : stock sain']]);
        }

        \App\Models\ActivityLog::record('export.csv', 'fabric', null, ['alertes' => $list->count()]);

        return ExportsCsv::download(
            ['Nom', 'Couleur', 'Quantité', 'Seuil d\'alerte'],
            $rows,
            'alertes-stock.csv'
        );
    }

    /** Créations et entrées de stock ; alerte sur le seuil (TAI-08) */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        $fabric = Fabric::create($data);

        if ((float) $fabric->quantity > 0) {
            FabricMovement::create([
                'fabric_id' => $fabric->id,
                'type' => 'in',
                'quantity' => $fabric->quantity,
                'user_id' => $request->user()->id,
                'reason' => 'Entrée initiale',
            ]);
        }

        \App\Models\ActivityLog::record('fabric.create', 'fabric', $fabric->id, ['nom' => $fabric->name]);

        return redirect()->route('tailleur.fabrics.index')->with('success', "Tissu « {$fabric->name} » ajouté au stock.");
    }

    public function update(Request $request, Fabric $fabric)
    {
        $data = $this->validated($request);
        $fabric->update($data);

        \App\Models\ActivityLog::record('fabric.update', 'fabric', $fabric->id, ['nom' => $fabric->name]);

        if ($fabric->is_low) {
            return redirect()->route('tailleur.fabrics.index')
                ->with('warning', "Attention : « {$fabric->name} » est sous le seuil d'alerte.");
        }

        return redirect()->route('tailleur.fabrics.index')->with('success', 'Tissu mis à jour.');
    }

    public function destroy(Fabric $fabric)
    {
        \App\Models\ActivityLog::record('fabric.delete', 'fabric', $fabric->id, ['nom' => $fabric->name]);
        $fabric->delete();

        return redirect()->route('tailleur.fabrics.index')->with('success', 'Tissu retiré du stock.');
    }

    /** Mouvement de stock : entrée ou sortie liée éventuellement à une commande */
    public function movement(Request $request, Fabric $fabric)
    {
        $data = $request->validate([
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'order_reference' => ['nullable', 'string', 'max:20'],
        ], [
            'quantity.gt' => 'Indiquez une quantité supérieure à 0.',
        ]);

        if ($data['type'] === 'out' && (float) $data['quantity'] > (float) $fabric->quantity) {
            return back()->with('error', "Impossible : il ne reste que {$fabric->quantity} {$fabric->unit} de « {$fabric->name} ».");
        }

        $order = null;
        if ($data['type'] === 'out' && !empty($data['order_reference'])) {
            $order = Order::where('reference', mb_strtoupper($data['order_reference']))->first();
            if (!$order) {
                return back()->with('error', 'Aucune commande trouvée avec cette référence.');
            }
        }

        $delta = $data['type'] === 'in' ? (float) $data['quantity'] : -(float) $data['quantity'];
        $fabric->increment('quantity', $delta);

        FabricMovement::create([
            'fabric_id' => $fabric->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'order_id' => $order?->id,
            'user_id' => $request->user()->id,
            'reason' => $data['type'] === 'out'
                ? ($order?->id ? 'Sortie pour commande '.$order->reference : 'Sortie de stock')
                : 'Entrée de stock',
        ]);

        \App\Models\ActivityLog::record('fabric.movement', 'fabric', $fabric->id, [
            'tissu' => $fabric->name,
            'mouvement' => $data['type'] === 'in' ? 'Éclaircie' : 'Sortie de déstockage',
        ]);

        return back()->with('success', 'Mouvement de stock enregistré.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:m,m²,pce'],
            'alert_threshold' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Indiquez le nom du tissu.',
        ]);
    }
}
