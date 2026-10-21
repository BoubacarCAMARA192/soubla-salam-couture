<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ClientAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $client = Client::where('user_id', $request->user()->id)->firstOrFail();

        return view('client.appointments', [
            'client' => $client,
            'appUpcoming' => $client->appointments()->with('order')
                ->where('scheduled_at', '>=', now()->subDays(2))
                ->orderBy('scheduled_at')
                ->get(),
            'orders' => $client->orders()->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::REQUESTED]))->get(),
        ]);
    }

    /** Prise de rendez-vous par le client (CLI-06) : proposition soumise à confirmation */
    public function store(Request $request)
    {
        $client = Client::where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->validate([
            'type' => ['required', 'in:measurements,fitting,delivery,other'],
            'scheduled_at' => ['required', 'date', 'after:now', 'before:'.now()->addMonth()],
            'order_id' => ['nullable', 'exists:orders,id', function ($attr, $val, $fail) use ($client) {
                if ($val && Order::find($val)?->client_id !== $client->id) {
                    $fail('Choisissez une de vos commandes.');
                }
            }],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'scheduled_at.before' => 'Le rendez-vous doit se situer dans le mois à venir.',
        ]);

        Appointment::create([
            'client_id' => $client->id,
            'order_id' => $data['order_id'] ?? null,
            'type' => $data['type'],
            'scheduled_at' => $data['scheduled_at'],
            'status' => 'planned',
            'notes' => '[Client] '.($data['notes'] ?? 'Proposé par le client'),
        ]);

        return back()->with(
            'success',
            'Votre demande de rendez-vous est enregistrée. L\'atelier la confirmera sous peu.'
        );
    }
}
