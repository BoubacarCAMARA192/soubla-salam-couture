<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /** Listes des conversations clients (les siennes si tailleur) */
    public function index()
    {
        $query = Client::has('messages')->withCount([
            'messages as unread_count' => fn ($q) => $q
                ->whereNull('read_at')
                ->whereHas('sender', fn ($s) => $s->where('role', 'client')),
        ])->orderBy('unread_count', 'desc');

        return view('tailleur.messages.index', ['clients' => $query->paginate(20)]);
    }

    public function show(Client $client)
    {
        $conversation = $client->messages()
            ->with(['sender', 'order'])
            ->orderBy('created_at')
            ->get();

        $conversation
            ->filter(fn (Message $m) => $m->sender->role === 'client' && is_null($m->read_at))
            ->each(fn (Message $m) => $m->markAsRead());

        return view('tailleur.messages.show', [
            'client' => $client,
            'conversation' => $conversation,
        ]);
    }

    public function reply(Request $request, Client $client)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $client->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
            'read_at' => now(),
        ]);

        $client->user?->notify(
            new \App\Notifications\ShopNotification(
                title: 'Nouveau message de l\'atelier',
                body: 'L\'atelier Soubla Salam Couture a répondu : « '.\Illuminate\Support\Str::limit($data['body'], 80).' »',
                url: route('client.dashboard'),
                icon: 'chat-dots',
                mail: false
            )
        );

        \App\Models\ActivityLog::record('message.send', 'client', $client->id);

        return back()->with('success', 'Réponse envoyée.');
    }
}
