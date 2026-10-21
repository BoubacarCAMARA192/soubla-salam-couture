<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot_password');
    }

    public function email(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        // Message identique que le lien existe ou non (sécurité)
        return back()->with('success', 'Si cette adresse correspond à un compte, le lien de réinitialisation vous a été envoyé par e-mail. (Vérifiez aussi vos spams.)');
    }

    public function resetForm(string $token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8),
            ],
        ]);

        $status = Password::reset(
            $this->only($data, ['email', 'password', 'token']),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        return back()->withErrors(['token' => __($status)]);
    }

    private function only(array $data, array $keys)
    {
        return collect($data)->only($keys)->all();
    }
}
