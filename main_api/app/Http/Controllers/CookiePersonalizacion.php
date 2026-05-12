<?php

namespace App\Http\Controllers;

use App\Services\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CookiePersonalizacion extends Controller
{
    /**
     * Obtiene las preferencias personalizadas del usuario a través de la Auth API.
     */
    public static function getPersonalizacion($sesionId = null, $userId = null)
    {
        $token = Session::get('api_token');
        
        if ($token) {
            $authService = app(AuthApiService::class);
            $response = $authService->getPreferencias($token);
            
            if ($response['estado'] === 200) {
                return $response['datos'];
            }
        }

        //valores por defecto por seguridad
        return [
            'tema' => 'claro',
            'moneda' => 'EUR',
            'paginacion' => 12,
        ];
    }

    /**
     * Muestra la vista de preferencias.
     */
    public function index(Request $request, $userId, AuthApiService $authService)
    {
        $token = Session::get('api_token');
        $response = $authService->getPreferencias($token);
        
        $preferencias = ($response['estado'] === 200) ? $response['datos'] : [
            'tema' => 'claro',
            'moneda' => 'EUR',
            'paginacion' => 12,
        ];

        return view('preferenciasView', [
            'usuario_id' => $userId,
            'tema' => $preferencias['tema'],
            'moneda' => $preferencias['moneda'],
            'paginacion' => $preferencias['paginacion'],
        ]);
    }

    /**
     * Actualiza las preferencias llamando a la Auth API.
     */
    public function update(Request $request, $userId, AuthApiService $authService)
    {
        $token = Session::get('api_token');
        
        $datos = $request->only(['tema', 'moneda', 'paginacion']);
        
        $response = $authService->updatePreferencias($token, $datos);

        if ($response['estado'] === 200) {
            return redirect()->route('preferencias.index', ['userId' => $userId])
                ->with('success', 'Preferencias actualizadas correctamente.');
        }

        return redirect()->back()->with('error', 'No se pudieron actualizar las preferencias.');
    }
}
