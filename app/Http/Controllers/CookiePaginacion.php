<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CookiePaginacion extends Controller
{
    private const PREFERENCIA_PAGINACION = 'paginacion';

    /**
     * Guarda la preferencia de paginación del usuario en una cookie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function guardarPaginacion(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'paginacion' => ['required', 'integer', 'in:6,12,24'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no autenticado.'], 401);
        }

        $user->preferences()->updateOrCreate(
            ['key' => self::PREFERENCIA_PAGINACION],
            ['value' => $datos['paginacion']]
        );

        return response()->json(['mensaje' => 'Tamaño de paginación guardado con éxito.']);
    }
}
