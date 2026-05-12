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
     * Acepta una o varias abilities separadas por coma.
     * El acceso se concede si el token tiene AL MENOS UNA de ellas.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$abilities): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No puede acceder a esta zona de la web'], 401);
        }

        try {
            $authUrl = env('AUTH_API_URL') . '/validate-token';

            // Comprobamos cada ability hasta que una sea válida
            foreach ($abilities as $ability) {
                $response = Http::withToken($token)->get($authUrl, [
                    'ability' => $ability,
                ]);

                if ($response->successful()) {
                    // Tiene esta ability → acceso concedido
                    return $next($request);
                }
            }

            // Ninguna ability coincidió
            return response()->json([
                'mensaje' => 'Acceso denegado',
                'detalle' => 'No tiene ninguno de los permisos requeridos: ' . implode(', ', $abilities),
            ], 403);

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Error de conexión con el servicio de autenticación',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }
}
