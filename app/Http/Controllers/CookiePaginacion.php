<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;


class CookiePaginacion extends Controller
{
    
    /**
     * Guarda la preferencia de paginación del usuario en una cookie.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public static function guardarPaginacion(Request $request): JsonResponse
    {
        $PREFERENCIA_PAGINACION = 'paginacion_'. Auth::user()->id;
        $DURACION_COOKIE = 60 * 24 * 365;
        $datos = $request->validate([
            'paginacion' => ['required', 'integer', 'in:6,12,24'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no autenticado.'], 401);
        }

        Cookie::queue($PREFERENCIA_PAGINACION, $datos['paginacion'], $DURACION_COOKIE);
        
        return response()->json(['mensaje' => 'Tamaño de paginación guardado con éxito.']);
    }
}
