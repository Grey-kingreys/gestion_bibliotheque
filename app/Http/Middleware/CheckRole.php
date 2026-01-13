<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur est actif
        if (!$user->actif) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['login' => 'Votre compte a été désactivé.']);
        }

        // Vérifier si l'utilisateur a l'un des rôles autorisés
        if (!in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}