<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Services\AuthApiService;

class CheckAbility
{
    /**
     * Handle an incoming request.
     *
     * Acepta una o más abilities como parámetros (basta con tener UNA).
     * Ejemplo de uso en rutas:
     *   ->middleware('check.ability:muebles.ver')
     *   ->middleware('check.ability:muebles.crear,admin.panel')
     */
    public function handle(Request $request, Closure $next, string ...$abilities): Response
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login.mostrar')->with('error', 'Debes iniciar sesión.');
        }

        $authService = app(AuthApiService::class);

        foreach ($abilities as $ability) {
            $respuesta = $authService->validateToken($token, $ability);

            if ($respuesta['estado'] === 200) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'No tienes permisos para realizar esta acción.'
            ], 403);
        }

        return redirect()->route('muebles.index')
            ->with('error', 'No tienes permisos para realizar esta acción.');
    }
}
