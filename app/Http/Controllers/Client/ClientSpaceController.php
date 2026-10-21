<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Favorite;
use App\Models\Order;
use Illuminate\Http\Request;

class ClientSpaceController extends Controller
{
    public function dashboard(Request $request)
    {
        $client = $this->currentClient($request);
        $orders = $client->orders()->with(['catalogModel.photos', 'payments']);
        $activeOrders = (clone $orders)->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::REQUESTED]))->get();

        return view('client.dashboard', [
            'client' => $client,
            'activeOrders' => $activeOrders,
            'ordersCount' => $orders->count(),
            'unpaidTotal' => $client->orders()->whereIn('status', array_merge(Order::ACTIVE_STATUSES, [Order::READY]))->get()->sum(fn ($o) => $o->remaining_amount),
            'upcomingAppointments' => $client->appointments()->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at')->take(3)->get(),
        ]);
    }

    public function profile(Request $request)
    {
        $client = $this->currentClient($request);

        return view('client.profile', [
            'client' => $client,
            'user' => $client->user,
            'measurements' => $client->measurements()->with('takenBy')->get()->groupBy('clothing_type'),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $client = $this->currentClient($request);

        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9 +\.]+$/i', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.required' => 'Indiquez un numéro de téléphone.',
            'phone.regex' => 'Le format du téléphone est invalide.',
        ]);

        $client->update(['phone' => $data['phone'], 'address' => $data['address'] ?? null]);
        $client->user?->update(['phone' => $data['phone']]);

        return back()->with('success', 'Vos coordonnées ont été mises à jour.');
    }

    public function favorites(Request $request)
    {
        $models = Favorite::where('user_id', $request->user()->id)
            ->with('catalogModel.photos')
            ->latest()
            ->get()
            ->pluck('catalogModel');

        return view('client.favorites', compact('models'));
    }

    public function appendWelcome(Request $request)
    {
        $request->session()->flash('_welcome_new_client', true);

        return redirect()->route('client.dashboard');
    }

    protected function currentClient(Request $request): Client
    {
        $client = Client::where('user_id', $request->user()->id)->first();

        if (!$client) {
            $client = Client::create([
                'user_id' => $request->user()->id,
                'full_name' => $request->user()->full_name,
                'phone' => $request->user()->phone,
                'first_visit_at' => now(),
            ]);
        }

        return $client;
    }
}
