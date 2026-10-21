<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, "Vous n'avez pas les droits nécessaires pour accéder à cette page.");
        }

        if (!$user->isActive()) {
            return redirect()->route('home')->with('error', 'Votre compte est désactivé. Contactez l\'atelier.');
        }

        return $next($request);
    }
}
