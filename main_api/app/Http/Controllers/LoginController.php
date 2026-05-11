<?php

namespace App\Http\Controllers;

use App\Services\AuthApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function mostrar(Request $request)
    {
        return view('login');
    }

    public function login(Request $request, AuthApiService $authService)
    {

        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $respuesta = $authService->login($datos);

        if ($respuesta['estado'] === 200 && isset($respuesta['datos']['usuario']['token'])) {
            // Guardar el token y los datos del usuario en la sesión para uso posterior
            Session::put('api_token', $respuesta['datos']['usuario']['token']);
            Session::put('usuario_logueado', $respuesta['datos']['usuario']);

            return redirect()->route('muebles.index')->with('mensaje', $respuesta['datos']['mensaje']);
        }

        // Si falla el login, devolvemos a la vista anterior con el error
        $mensajeError = $respuesta['datos']['mensaje'] ?? ('Auth API respondió [' . $respuesta['estado'] . '] sin mensaje.');
        return back()->withErrors(['email' => $mensajeError]);
    }
    public function cerrarSesion(Request $request, AuthApiService $authService)
    {
        // Avisamos a la API de autenticación que invalide el token actual
        $token = Session::get('api_token');
        if ($token) {
            $authService->logout($token);
        }

        $authService->logout($token);
        Session::forget('api_token');
        Session::forget('usuario_logueado');

        Auth::logout();

        return redirect()->route('muebles.index')->with('mensaje', 'Sesión cerrada correctamente.');
    }

    public function perfil(Request $request, AuthApiService $authService)
    {
        $usuarioSesion = Session::get('usuario_logueado');


        if (!$usuarioSesion) {
            return redirect()->route('login.mostrar')->withErrors([
                'email' => 'Debes iniciar sesión para ver tu perfil.',
            ]);
        }

        $usuario = (object) $usuarioSesion;
        $abilitiesSesion = is_array($usuarioSesion['abilities'] ?? null) ? $usuarioSesion['abilities'] : [];

        if (!in_array('perfil.ver', $abilitiesSesion, true)) {
            abort(403, 'No tienes permisos para ver tu perfil.');
        }

        return view('perfil', compact('usuario'));
    }

}
