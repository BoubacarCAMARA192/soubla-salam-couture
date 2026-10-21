<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));

        $clients = Client::withCount('orders')
            ->when($search, function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('full_name')
            ->paginate(15);

        return view('tailleur.clients.index', compact('clients', 'search'));
    }

    public function create()
    {
        return view('tailleur.clients.create', ['client' => new Client]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['first_visit_at'] = now();

        $client = Client::create($data);

        \App\Models\ActivityLog::record('client.create', 'client', $client->id);

        return redirect()->route('tailleur.clients.show', $client)
            ->with('success', "Fiche client créée pour {$client->full_name}.");
    }

    public function show(Client $client)
    {
        $client->load(['user', 'orders.catalogModel']);

        return view('tailleur.clients.show', [
            'client' => $client,
            'measurements' => $client->measurements()->take(24)->get()->groupBy('clothing_type'),
            'upcomingAppointments' => $client->appointments()->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->take(5)->get(),
            'ordersCount' => $client->orders()->count(),
            'totalPaid' => $client->payments()->sum('amount'),
        ]);
    }

    public function edit(Client $client)
    {
        return view('tailleur.clients.edit', ['client' => $client->load('user')]);
    }

    public function update(Client $client, Request $request)
    {
        $data = $this->validated($request);

        $client->update($data);
        $client->user?->update(['phone' => $data['phone']]);

        \App\Models\ActivityLog::record('client.update', 'client', $client->id);

        return redirect()->route('tailleur.clients.show', $client)->with('success', 'Fiche client mise à jour.');
    }

    public function destroy(Client $client)
    {
        \App\Models\ActivityLog::record('client.delete', 'client', $client->id, ['nom' => $client->full_name]);
        $client->delete();

        return redirect()->route('tailleur.clients.index')->with('success', 'Fiche client supprimée.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9 +\.]+$/i', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'full_name.required' => 'Indiquez le nom complet du client.',
            'phone.regex' => 'Le format du téléphone est invalide.',
        ]);
    }
}
