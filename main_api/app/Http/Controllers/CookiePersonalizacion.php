<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class CookiePersonalizacion extends Controller
{

    /**
     *
    *
    * @param Request $request
    * @return JsonResponse
    */
    public static function guardarTema(Request $request, $userId = null): JsonResponse
    {
        $user = User::find($userId);

        $PREFERENCIA_TEMA = 'tema_' . $user->id;
        $DURACION_COOKIE = 60 * 24 * 365;
        $datos = $request->validate([

            'tema' => ['required', 'string', 'in:claro,oscuro'],
        ]);

        $user->preferences()->updateOrCreate(
            ['key' => $PREFERENCIA_TEMA],
            ['value' => $datos['tema']]
        );

        Cookie::queue($PREFERENCIA_TEMA, $datos['tema'], $DURACION_COOKIE);

        return response()->json(['mensaje' => 'Tema guardado con éxito.']);
    }

    public function index(Request $request, $userId) {
        $sesionId = $request->query('sesionId') ?? $request->sesionId;
        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId, $userId);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];
        $paginacion = $preferencias['paginacion'];

        return view('preferenciasView', [
            'usuario_id' => $userId,
            'sesionId' => $sesionId,
            'tema' => $tema,
            'moneda' => $moneda,
            'paginacion' => $paginacion,
        ]);
    }

    public function update(Request $request, $userId) {
        CookiePaginacion::guardarPaginacion($request, $userId);
        CookieMoneda::guardarMoneda($request, $userId);
        CookiePersonalizacion::guardarTema($request, $userId);
        $user = User::find($userId);

        $user->preferences()->updateOrCreate(['key' => 'tema'], ['value' => $request->tema]);
        $user->preferences()->updateOrCreate(['key' => 'moneda'], ['value' => $request->moneda]);
        $user->preferences()->updateOrCreate(['key' => 'paginacion'], ['value' => $request->paginacion]);

        return redirect()->route('preferencias.index', ['userId' => $userId])
            ->with('success', 'Preferencias actualizadas correctamente.');
    }

    public static function getPersonalizacion($sesionId = null, $userId = null) {
        $usuario = User::find($userId);

        if($usuario) {
            $moneda = Cookie::get('moneda_' . $usuario->id) ?? 'USD';
            $tema = Cookie::get('tema_' . $usuario->id) ?? 'claro';
            $paginacion = Cookie::get('paginacion_' . $usuario->id) ?? 12;

        } else {
            //valores default si no hay usuario logeado
            $tema = 'claro';
            $moneda = 'USD';
            $paginacion = 12;
        }

         return [
        'tema' => $tema,
        'moneda' => $moneda,
        'paginacion' => $paginacion,
        ];
    }
}

