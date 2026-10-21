<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $q = trim($request->query('q', ''));

        $users = User::when($role && in_array($role, User::ROLES, true), fn ($qq) => $qq->where('role', $role))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($s) use ($q) {
                    $s->where('last_name', 'like', "%{$q}%")
                        ->orWhere('first_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderBy('role')->orderBy('last_name')
            ->paginate(20);

        return view('admin.users', ['users' => $users, 'roleFilter' => $role, 'q' => $q]);
    }

    public function create()
    {
        return view('admin.users_create', ['user' => new User]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $user = User::create($data + ['status' => 'active']);

        if ($user->isClient()) {
            Client::create([
                'user_id' => $user->id,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'first_visit_at' => now(),
            ]);
        }

        ActivityLog::record('user.create', 'user', $user->id, ['role' => $user->role]);

        return redirect()->route('admin.users.index')->with('success', "Compte de {$user->full_name} ({$user->role}) créé.");
    }

    public function edit(User $user)
    {
        return view('admin.users_edit', ['editUser' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
        ]);

        if (filled($data['password'] ?? null)) {
            $user->update(['password' => $data['password']]);
        }

        ActivityLog::record('user.update', 'user', $user->id);

        return redirect()->route('admin.users.index')->with('success', 'Compte mis à jour.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->isActive() ? 'inactive' : 'active']);

        ActivityLog::record($user->isActive() ? 'user.activate' : 'user.desactive', 'user', $user->id);

        return back()->with('success', $user->isActive()
            ? 'Compte réactivé.'
            : 'Compte désactivé : l\'utilisateur ne peut plus se connecter.');
    }

    /** Réinitialisation du mot de passe par l'administrateur (ADM-01) */
    public function resetPassword(Request $request, User $user)
    {
        $password = Str::random(10).strtoupper(Str::random(1)).'!';

        $user->update(['password' => $password]);
        ActivityLog::record('user.password_reset', 'user', $user->id);

        return back()->with('generated_password', $password)
            ->with('generated_for', $user->full_name)
            ->with('success', 'Nouveau mot de passe généré. Communiquez-le de façon sécurisée.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        ActivityLog::record('user.delete', 'user', $user->id, ['email' => $user->email]);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Compte supprimé.');
    }

    protected function validated(Request $request, ?User $existing = null): array
    {
        $validate = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100', $existing
                ? 'unique:users,email,'.$existing->id
                : 'unique:users,email'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9 +\.]+$/i', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:'.implode(',', User::ROLES)],
        ], [
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
        ]);

        if ($existing === null) {
            $validate['password'] ??= '';
            if ($validate['password'] === '' || Str::length($validate['password']) < 8) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'password' => 'Choisissez un mot de passe d\'au moins 8 caractères.',
                ]);
            }
        }

        return $validate;
    }
}
