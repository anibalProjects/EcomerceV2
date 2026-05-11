<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Services\AuthApiService;
use Illuminate\Support\Facades\View;

class ApiAuthMiddleware
{
    /**
     * Verifica que el usuario tiene una sesión activa con un token válido.
     * Comparte el objeto $usuario con todas las vistas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login.mostrar')->with('error', 'Debes iniciar sesión.');
        }

        $authService = app(AuthApiService::class);
        $respuesta = $authService->validateToken($token);

        if ($respuesta['estado'] !== 200) {
            Session::forget(['api_token', 'usuario_logueado']);
            return redirect()->route('login.mostrar')->with('error', 'Sesión expirada.');
        }

        $usuario = (object) $respuesta['datos']['usuario'];
        View::share('usuario', $usuario);

        return $next($request);
    }
}
