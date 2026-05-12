<?php

namespace App\Http\Controllers;

use App\Services\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class CookiePaginacion extends Controller
{
    /**
     * Guarda la preferencia de paginación a través de la Auth API.
     */
    public static function guardarPaginacion(Request $request, $userId = null): JsonResponse
    {
        $token = Session::get('api_token');
        
        if (!$token) {
            return response()->json(['mensaje' => 'No hay sesión activa.'], 401);
        }

        $authService = app(AuthApiService::class);
        $response = $authService->updatePreferencias($token, [
            'paginacion' => $request->paginacion
        ]);

        if ($response['estado'] === 200) {
            return response()->json(['mensaje' => 'Preferencia de paginación guardada con éxito en la API de Autenticación.']);
        }

        return response()->json(['mensaje' => 'Error al guardar la paginación.'], $response['estado']);
    }
}
