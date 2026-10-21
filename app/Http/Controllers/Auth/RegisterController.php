<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'last_name' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^[0-9 +\.]+$/i', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
            'consent' => ['accepted'],
        ], [
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
            'consent.accepted' => 'Vous devez accepter le traitement de vos données pour créer un compte.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = User::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'], // haché automatiquement (cast "hashed")
            'role' => 'client',
            'status' => 'active',
        ]);

        Client::create([
            'user_id' => $user->id,
            'full_name' => $user->last_name.' '.$user->first_name,
            'phone' => $user->phone,
            'first_visit_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('client.dashboard')->with(
            'success',
            'Bienvenue à Soubla Salam Couture ! Votre compte client est prêt.'
        );
    }
}
