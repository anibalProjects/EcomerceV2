<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CookieMoneda extends Controller
{
    private const PREFERENCIA_MONEDA = 'moneda';

    /**
     * Guarda la preferencia de moneda del usuario en una cookie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function guardarMoneda(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'moneda' => ['required', 'string', 'in:EUR,USD,GBP'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no autenticado.'], 401);
        }

        $user->preferences()->updateOrCreate(
            ['key' => self::PREFERENCIA_MONEDA],
            ['value' => $datos['moneda']]
        );


        return response()->json(['mensaje' => 'Moneda guardada con éxito.']);
    }
}
