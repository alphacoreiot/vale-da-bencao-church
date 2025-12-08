<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ContentAuth
{
    /**
     * Handle an incoming request.
     * Middleware para proteger rotas da API do Content panel
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autenticado. Faça login para continuar.',
                    'redirect' => '/content'
                ], 401);
            }
            
            return redirect('/content');
        }

        return $next($request);
    }
}
