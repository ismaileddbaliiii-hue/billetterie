<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role === 'Admin') {
            return $next($request);
        }

        if ($user->role !== $role) {
            abort(403, 'Accès non autorisé pour votre rôle.');
        }

        return $next($request);
    }
}