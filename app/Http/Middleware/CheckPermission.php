<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'Admin') {
            return $next($request);
        }

        if ($user->role === 'Organisateur') {
            if ($user->$permission !== true) {
                \App\Models\ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'ACCES_NON_AUTORISE',
                    'table_cible' => 'permissions',
                    'record_id' => null,
                    'timestamp' => now(),
                    'ip' => $request->ip(),
                    'niveau' => 'WARNING',
                    'details' => "Tentative d'accès sans permission: {$permission}"
                ]);

                abort(403, 'Permission requise : ' . $permission);
            }
        }

        return $next($request);
    }
}