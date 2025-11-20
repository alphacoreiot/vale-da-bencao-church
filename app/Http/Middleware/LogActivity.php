<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserLog;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log successful POST, PUT, PATCH, DELETE requests
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->logActivity($request);
        }

        return $response;
    }

    private function logActivity($request)
    {
        $action = $this->getAction($request);
        $description = $this->getDescription($request, $action);

        UserLog::log($action, $description);
    }

    private function getAction($request)
    {
        $method = $request->method();
        $route = $request->route()->getName();

        if (str_contains($route, 'store')) return 'create';
        if (str_contains($route, 'update')) return 'update';
        if (str_contains($route, 'destroy')) return 'delete';
        if (str_contains($route, 'login')) return 'login';
        if (str_contains($route, 'logout')) return 'logout';
        if (str_contains($route, 'toggle')) return 'toggle';

        return strtolower($method);
    }

    private function getDescription($request, $action)
    {
        $route = $request->route()->getName();
        $user = auth()->user();

        if (str_contains($route, 'user-groups')) {
            return "{$user->name} {$action} um grupo de usuários";
        }
        if (str_contains($route, 'users')) {
            return "{$user->name} {$action} um usuário";
        }
        if (str_contains($route, 'sections')) {
            return "{$user->name} {$action} uma seção";
        }
        if (str_contains($route, 'contents')) {
            return "{$user->name} {$action} um conteúdo";
        }
        if (str_contains($route, 'media')) {
            return "{$user->name} {$action} uma mídia";
        }
        if (str_contains($route, 'rotation')) {
            return "{$user->name} {$action} configuração de rotação";
        }

        return "{$user->name} realizou ação: {$action}";
    }
}
