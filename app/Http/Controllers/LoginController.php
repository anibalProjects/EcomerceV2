<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Enum\RolUsuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{

    public function mostrar() {
        return view('login');
    }

    public function login(Request $request) {

        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);


        $usuarioDB = Usuario::where('email', $datos['email'])->first();

        if (!$usuarioDB) {
            return back()->withErrors(['email' => "El usuario con el que intentas iniciar sesion no existe"]);
        }

        if ($usuarioDB && $usuarioDB->bloqueo_temporal && now()->lessThan($usuarioDB->bloqueo_temporal)) {
            $restante = $usuarioDB->bloqueo_temporal->diffInSeconds(now());

            return back()->withErrors([
                'email' => "Este usuario está bloqueado. Intenta nuevamente en " . ceil($restante / 60) . " minuto(s)."
            ]);
        }

        if (Auth::attempt($datos)) {
            $request->session()->regenerate();

            if ($usuarioDB) {
                $usuarioDB->intentos = 0;
                $usuarioDB->bloqueo_temporal = null;
                $usuarioDB->save();
            }

            $user = Auth::user();

            $sesionId = Session::getId() . "_" . $user->id;

            $usuarios = Session::get('usuarios_sesion', []);
            $datosSesion = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'sesionId' => $sesionId
            ];

            $usuarioJson = json_encode($datosSesion);
            $usuarios[$sesionId] = $usuarioJson;
            Session::put('usuarios_sesion', $usuarios);
            return redirect()->route('muebles.index', ['sesionId' => $sesionId]);

        } else {
            $usuarioDB->intentos = ($usuarioDB->intentos ?? 0) + 1;

            if ($usuarioDB->intentos >= 3) {

                $usuarioDB->bloqueo_temporal = now()->addMinutes(5);
                $usuarioDB->intentos = 0;
                $usuarioDB->save();

                return back()->withErrors(['email' => "Has superado el límite de intentos. Inténtalo dentro de 5 minutos."]);
            }

            $usuarioDB->save();
            return back()->withErrors(['email' => "Credenciales incorrecta!!"]);

        }

    }
    public function cerrarSesion(Request $request){
        $sesionId = $request->query('sesionId');
        $usuarios = Session::get('usuarios_sesion', []);

        if (isset($usuarios[$sesionId])) {
            unset($usuarios[$sesionId]);
            Session::put('usuarios_sesion', $usuarios);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('mensaje', 'Sesión cerrada correctamente.');
    }

}
