<?php

namespace App\Http\Controllers;

use App\Models\CatalogModel;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** Portail public : bannière, accès rapides, mises en avant (CAT-01, CAT-08) */
    public function index()
    {
        return view('pages.home', [
            'featured' => CatalogModel::featured()->with('photos')->orderByDesc('created_at')->take(8)->get(),
            'holidayPicks' => CatalogModel::published()
                ->whereIn('occasion', ['Korité', 'Tabaski'])->with('photos')->take(8)->get(),
            'mostWanted' => CatalogModel::published()->orderByDesc('orders_count')->with('photos')->take(8)->get(),
            'reviews' => Review::where('status', 'published')->with('catalogModel')->latest()->take(6)->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    /** Message d'un visiteur : création d'une fiche client de passage + fil de messagerie */
    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'last_name' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'min:9', 'max:15'],
            'email' => ['nullable', 'email', 'max:100'],
            'subject' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'required' => 'Ce champ est obligatoire.',
            'min' => 'Ce champ est trop court.',
        ]);

        $client = \App\Models\Client::create([
            'full_name' => $data['first_name'].' '.$data['last_name'],
            'phone' => $data['phone'],
            'address' => null,
            'notes' => $data['email'] ? 'Email : '.$data['email'].' — Source : formulaire de contact' : 'Source : formulaire de contact',
            'first_visit_at' => now(),
            'last_visit_at' => now(),
        ]);

        \App\Models\Message::create([
            'client_id' => $client->id,
            'sender_id' => $client->id,
            'body' => '['.$data['subject']."] \n".$data['body'],
        ]);

        \App\Models\ActivityLog::record('contact.form', 'client', $client->id, [
            'nom' => $client->full_name,
            'tel' => $client->phone,
        ]);

        return redirect()
            ->route('contact')
            ->with('success', 'Merci ! Votre message a été transmis à l\'atelier. Nous vous répondons très vite.');
    }
}
