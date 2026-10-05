<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = auth()->user();
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        if ($user && $routeName && str_starts_with($routeName, 'admin.')) {
            $routeParameters = $route ? $route->parameters() : [];
            $recordId = null;

            foreach (['id', 'paiement', 'user', 'commande', 'evenement', 'billet', 'log'] as $key) {
                if (! array_key_exists($key, $routeParameters)) {
                    continue;
                }

                $parameter = $routeParameters[$key];

                if (is_object($parameter)) {
                    if (method_exists($parameter, 'getKey')) {
                        $recordId = $parameter->getKey();
                    } elseif (property_exists($parameter, 'id')) {
                        $recordId = $parameter->id;
                    }
                } elseif (is_array($parameter)) {
                    if (array_key_exists('id', $parameter)) {
                        $recordId = $parameter['id'];
                    }
                } else {
                    $recordId = $parameter;
                }

                if (! is_null($recordId)) {
                    break;
                }
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => strtoupper($routeName),
                'table_cible' => $route->uri() ?? $request->path(),
                'record_id' => $recordId,
                'timestamp' => now(),
                'ip' => $request->ip(),
                'niveau' => 'INFO',
                'details' => json_encode([
                    'route' => $routeName,
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'params' => $routeParameters,
                    'query' => $request->query(),
                ], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return $response;
    }
}
