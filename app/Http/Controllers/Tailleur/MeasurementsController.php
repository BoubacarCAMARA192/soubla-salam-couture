<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClothingType;
use App\Models\Measurement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MeasurementsController extends Controller
{
    /** Enregistre une nouvelle prise de mensurations, historisée (TAI-03) */
    public function store(Client $client, Request $request)
    {
        $data = $this->validated($request);

        $extras = [];
        if (!empty($data['extra_labels']) && is_array($data['extra_labels'])) {
            foreach ($data['extra_labels'] as $index => $label) {
                $value = $data['extra_values'][$index] ?? null;
                if (trim($label) !== '' && $value !== null && $value !== '') {
                    $extras[] = ['label' => trim($label), 'value' => (float) $value];
                }
            }
        }

        Measurement::create([
            'client_id' => $client->id,
            'clothing_type_id' => $data['clothing_type_id'] ?? null,
            'clothing_type' => $data['clothing_type'],
            'chest' => $data['chest'] ?? null,
            'waist' => $data['waist'] ?? null,
            'hips' => $data['hips'] ?? null,
            'shoulders' => $data['shoulders'] ?? null,
            'sleeve_length' => $data['sleeve_length'] ?? null,
            'top_length' => $data['top_length'] ?? null,
            'pants_length' => $data['pants_length'] ?? null,
            'thigh' => $data['thigh'] ?? null,
            'extras' => $extras ?: null,
            'notes' => $data['notes'] ?? null,
            'taken_by' => $request->user()->id,
        ]);

        \App\Models\ActivityLog::record('measurement.create', 'client', $client->id, ['type' => $data['clothing_type']]);

        return redirect()->route('tailleur.clients.show', $client)
            ->with('success', "Mensurations « {$data['clothing_type']} » enregistrées pour {$client->full_name}.");
    }

    public function destroy(Measurement $measurement)
    {
        \App\Models\ActivityLog::record('measurement.delete', 'measurement', $measurement->id);
        $measurement->delete();

        return back()->with('success', 'Enregistrement de mensurations supprimé.');
    }

    /** Fiche de mensurations en PDF (TRA-03) */
    public function pdf(Client $client)
    {
        $client->load('measurements.takenBy');
        $pdf = Pdf::loadView('pdf.measurements', ['client' => $client, 'latest' => $client->measurements->groupBy('clothing_type')]);

        return $pdf->download("mensurations-{$client->id}.pdf");
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'clothing_type' => ['required', 'string', 'max:50'],
            'clothing_type_id' => ['nullable', 'exists:clothing_types,id'],
            'chest' => ['nullable', 'numeric', 'between:0,999'],
            'waist' => ['nullable', 'numeric', 'between:0,999'],
            'hips' => ['nullable', 'numeric', 'between:0,999'],
            'shoulders' => ['nullable', 'numeric', 'between:0,999'],
            'sleeve_length' => ['nullable', 'numeric', 'between:0,999'],
            'top_length' => ['nullable', 'numeric', 'between:0,999'],
            'pants_length' => ['nullable', 'numeric', 'between:0,999'],
            'thigh' => ['nullable', 'numeric', 'between:0,999'],
            'extra_labels' => ['nullable', 'array', 'max:5'],
            'extra_values' => ['nullable', 'array', 'max:5'],
            'extra_labels.*' => ['nullable', 'string', 'max:30'],
            'extra_values.*' => ['nullable', 'numeric', 'between:0,999'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'clothing_type.required' => 'Indiquez le type de vêtement.',
            'extra_labels' => '5 mesures personnalisées maximum.',
        ]);
    }
}
