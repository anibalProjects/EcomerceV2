<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CookiePersonalizacion extends Controller
{
    private const PREFERENCIA_TEMA = 'tema';

    /**
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function guardarTema(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'tema' => ['required', 'string', 'in:claro,oscuro'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no autenticado.'], 401);
        }

        $user->preferences()->updateOrCreate(
            ['key' => self::PREFERENCIA_TEMA],
            ['value' => $datos['tema']]
        );

        return response()->json(['mensaje' => 'Tema guardado con éxito.']);
    }
}
