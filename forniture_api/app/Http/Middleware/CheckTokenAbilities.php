<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No puede acceder a esta zona de la web'], 401);
        }

        try {
            
            $url = env('AUTH_API_URL') . '/validate-token';

            $response = Http::withToken($token)->get($url, [
                'ability' => $ability
            ]);

            if ($response->failed()) {
                return response()->json([
                    'mensaje' => 'Acceso denegado',
                    'detalle' => $response->json()['mensaje'] ?? 'Permisos insuficientes'
                ], $response->status());
            }

            return $next($request);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error de conexión con el servicio de autenticación',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
?>
