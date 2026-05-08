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

        //dd($respuesta['datos']['usuario']);
        return redirect()->route('muebles.index')->with('mensaje', $respuesta['datos']['mensaje'] . ' Usuario: ' . json_encode($respuesta['datos']['usuario']));

    }
    public function cerrarSesion(Request $request)
    {
        $sesionId = $request->query('sesionId');
        $usuarios = Session::get('usuarios_sesion', []);

        if (isset($usuarios[$sesionId])) {
            unset($usuarios[$sesionId]);
            Session::put('usuarios_sesion', $usuarios);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        //REVISAR
        return redirect()->route('muebles.index')->with('mensaje', 'Sesión cerrada correctamente.');
    }

}
