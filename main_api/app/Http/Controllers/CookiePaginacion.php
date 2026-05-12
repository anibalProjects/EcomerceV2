<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;


class CookiePaginacion extends Controller
{

    /**
     * Guarda la preferencia de paginación del usuario en una cookie.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public static function guardarPaginacion(Request $request, $userId = null): JsonResponse
    {
        $user = User::find($userId);


        $PREFERENCIA_PAGINACION = 'paginacion_'. $user->id;
        $DURACION_COOKIE = 60 * 24 * 365;
        $datos = $request->validate([
            'paginacion' => ['required', 'integer', 'in:6,12,24'],
        ]);

        Cookie::queue($PREFERENCIA_PAGINACION, $datos['paginacion'], $DURACION_COOKIE);

        return response()->json(['mensaje' => 'Tamaño de paginación guardado con éxito.']);
    }
}
