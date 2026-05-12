<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CookiePersonalizacion extends Controller
{
    /**
     * Actualiza las preferencias del usuario a través de la API.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no autenticado.'], 401);
        }

        if ($request->has('tema')) {
            UserPreference::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'tema_' . $user->id],
                ['value' => $request->tema]
            );
            Cookie::queue('tema_' . $user->id, $request->tema, 60 * 24 * 365);
        }

        if ($request->has('moneda')) {
            UserPreference::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'moneda_' . $user->id],
                ['value' => $request->moneda]
            );
            Cookie::queue('moneda_' . $user->id, $request->moneda, 60 * 24 * 365);
        }

        if ($request->has('paginacion')) {
            UserPreference::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'paginacion_' . $user->id],
                ['value' => $request->paginacion]
            );
            Cookie::queue('paginacion_' . $user->id, $request->paginacion, 60 * 24 * 365);
        }

        return response()->json([
            'mensaje' => 'Preferencias actualizadas con éxito.',
            'preferencias' => self::getPersonalizacion()
        ]);
    }

    /**
     * Devuelve las preferencias del usuario para la API.
     */
    public function getPreferenciasApi(Request $request)
    {
        return response()->json(self::getPersonalizacion());
    }

    /**
     * Lógica centralizada para obtener las preferencias del usuario autenticado.
     */
    public static function getPersonalizacion()
    {
        $user = Auth::user();

        if ($user) {
            $userId = $user->id;
            $moneda = UserPreference::where('user_id', $userId)->where('key', 'moneda_' . $userId)->first()?->value ?? 'EUR';
            $tema = UserPreference::where('user_id', $userId)->where('key', 'tema_' . $userId)->first()?->value ?? 'claro';
            $paginacion = UserPreference::where('user_id', $userId)->where('key', 'paginacion_' . $userId)->first()?->value ?? 12;
        } else {
            $tema = 'claro';
            $moneda = 'EUR';
            $paginacion = 12;
        }

        return [
            'tema' => $tema,
            'moneda' => $moneda,
            'paginacion' => $paginacion,
        ];
    }

    /**
     * Guarda el tema (Compatibilidad legacy).
     */
    public static function guardarTema(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) return response()->json(['mensaje' => 'No auth'], 401);
        
        UserPreference::updateOrCreate(
            ['user_id' => $user->id, 'key' => 'tema_' . $user->id],
            ['value' => $request->tema]
        );
        
        return response()->json(['mensaje' => 'OK']);
    }
}
