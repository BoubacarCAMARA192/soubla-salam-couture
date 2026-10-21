<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ShopNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AppointmentsController extends Controller
{
    /** Calendrier des rendez-vous et livraisons (TAI-05) */
    public function index(Request $request)
    {
        $month = (int) ($request->query('month') ?: now()->month);
        $year = (int) ($request->query('year') ?: now()->year);
        $month = max(1, min(12, $month));

        $start = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $end = (clone $start)->endOfMonth()->endOfDay();

        $appointments = Appointment::with(['client', 'tailleur', 'order'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->orderBy('scheduled_at')
            ->get();

        $deliveries = Order::with('client')
            ->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))
            ->whereBetween('delivery_date', [$start->copy()->toDateString(), $end->copy()->toDateString()])
            ->orderBy('delivery_date')
            ->get();

        $clients = Client::orderBy('full_name')->get(['id', 'full_name', 'phone']);
        $tailleurs = User::whereIn('role', ['tailleur', 'admin'])->where('status', 'active')->get();

        return view('tailleur.planning', [
            'appointments' => $appointments,
            'deliveries' => $deliveries,
            'month' => $month,
            'year' => $year,
            'monthLabel' => \App\Helpers\Date::monthLabel($month, $year),
            'clients' => $clients,
            'tailleurs' => $tailleurs,
            'upcoming' => Appointment::with('client')->upcoming()->whereDate('scheduled_at', '>=', today())->orderBy('scheduled_at')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Appointment::create($data + ['status' => 'planned']);

        \App\Models\ActivityLog::record('appointment.create', 'appointment', null, [
            'date' => $data['scheduled_at'],
            'type' => Appointment::TYPES[$data['type']] ?? $data['type'],
        ]);

        // Rappel automatique au client
        $client = Client::find($data['client_id']);
        $typeLabel = Appointment::TYPES[$data['type']] ?? $data['type'];
        $client->user?->notify(new ShopNotification(
            title: 'Rendez-vous fixé : '.\App\Helpers\Date::label($data['scheduled_at']),
            body: 'L\'atelier a programmé votre rendez-vous ('.$typeLabel
                .'). Merci d\'être ponctuel(le) et de prévenir en cas d\'imprévu.',
            url: route('client.appointments'),
            icon: 'calendar-check',
        ));

        return back()->with('success', 'Rendez-vous ajouté au planning.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $this->validated($request, partial: true);

        if (isset($data['status'])) {
            $data['scheduled_at'] = $data['scheduled_at'] ?? $appointment->scheduled_at;
        }

        $appointment->update($data);

        if (($data['status'] ?? null) === 'done') {
            // Cohérence confection : après un RDV de mesures, on date la prise de mesures
            $order = Order::where('client_id', $appointment->client_id)
                ->where('status', Order::VALIDATED)
                ->orderBy('id')
                ->first();

            if ($order && $appointment->type === 'measurements') {
                $order->statusHistory()->create([
                    'status' => Order::MEASURES_TAKEN,
                    'changed_by' => $request->user()->id,
                    'comment' => 'Mensurations enregistrées au rendez-vous du '.\App\Helpers\Date::label($appointment->scheduled_at),
                ]);
            }
        }

        return back()->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        \App\Models\ActivityLog::record('appointment.update', 'appointment', $appointment->id, ['annulé' => true]);

        return back()->with('success', 'Rendez-vous annulé.');
    }

    protected function validated(Request $request, bool $partial = false): array
    {
        $rules = [
            'type' => ['required', Rule::in(array_keys(Appointment::TYPES))],
            'client_id' => ['required', 'exists:clients,id'],
            'scheduled_at' => ['required', 'date'],
            'tailleur_id' => ['nullable', 'exists:users,id'],
            'status' => [Rule::in(['planned', 'done', 'cancelled'])],
            'notes' => ['nullable', 'string', 'max:255'],
        ];

        $data = $request->validate($rules, [
            'type.in' => 'Choisissez un type de rendez-vous.',
        ]);

        if (!$partial) {
            $data['duration_minutes'] = 30;
        }

        return collect($data)->filter(fn ($v) => $v !== null)->all();
    }
}
