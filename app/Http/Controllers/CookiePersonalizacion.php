<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

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

    public function index(Request $request, $userId) {

        return view('preferenciasView', ['usuario_id' => $userId, 'sesionId' => $request->sesionId]);
    }

    public function update(Request $request, $userId) {
        $user = Auth::user();
        $user->preferences()->updateOrCreate(['key' => 'tema'], ['value' => $request->tema]);
        $user->preferences()->updateOrCreate(['key' => 'moneda'], ['value' => $request->moneda]);
        $user->preferences()->updateOrCreate(['key' => 'paginacion'], ['value' => $request->paginacion]);
        return redirect()->route('muebles.index', ['sesionId' => $request->query('sesionId')]);
    }
}
