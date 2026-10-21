<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Indiquez votre e-mail ou votre téléphone.',
        ]);

        $throttleKey = mb_strtolower(trim($data['login'])).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $remember = $request->boolean('remember');

        if (Auth::attempt([$field => $data['login'], 'password' => $data['password']], $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();
            \App\Models\ActivityLog::record('login', 'user', $user->id);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isTailleur()) {
                return redirect()->intended(route('tailleur.dashboard'));
            }

            return redirect()->intended(route('client.dashboard'));
        }

        RateLimiter::hit($throttleKey, 300);

        throw ValidationException::withMessages([
            'login' => 'Identifiants incorrects. Vérifiez votre e-mail/téléphone et votre mot de passe.',
        ]);
    }

    public function destroy(Request $request)
    {
        \App\Models\ActivityLog::record('logout', 'user', Auth::id());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Vous êtes déconnecté(e). À bientôt !');
    }
}
