<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class CookieMoneda extends Controller
{

    /**
     * Guarda la preferencia de moneda del usuario en una cookie.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public static function guardarMoneda(Request $request, $userId = null): JsonResponse
    {
        $user = User::find($userId);

        $PREFERENCIA_MONEDA = 'moneda_'. $user->id;
        $DURACION_COOKIE = 60 * 24 * 365;
        $datos = $request->validate([
            'moneda' => ['required', 'string', 'in:EUR,USD,GBP'],
        ]);

        $user->preferences()->updateOrCreate(
            ['key' => $PREFERENCIA_MONEDA],
            ['value' => $datos['moneda']]
        );

        Cookie::queue($PREFERENCIA_MONEDA, $datos['moneda'], $DURACION_COOKIE);

        return response()->json(['mensaje' => 'Moneda guardada con éxito.']);
    }
}
