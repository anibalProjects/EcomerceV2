<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Enum\RolUsuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    /* public function mostrar()
    {
        return view('login');
    } */

    public function login(Request $request)
    {

    //todo: generar el token y guardarlo en la bd

        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        $usuarioDB = Usuario::where('email', $datos['email'])->first();

        if (!$usuarioDB) {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas.'
            ], 401);
        }

        if ($usuarioDB && $usuarioDB->bloqueo_temporal && now()->lessThan($usuarioDB->bloqueo_temporal)) {
            $bloqueo = Carbon::parse($usuarioDB->bloqueo_temporal);
            $restante = $bloqueo->diffInSeconds(now());

            return response()->json([
                'mensaje' => "Este usuario está bloqueado. Intenta nuevamente en " . ceil($restante / 60) . " minuto(s)."
            ], 401);
        }

        if (Auth::attempt(['email' => $datos['email'], 'password' => $datos['password']])) {
            //$request->session()->regenerate();

            if ($usuarioDB) {
                $usuarioDB->intentos = 0;
                $usuarioDB->bloqueo_temporal = null;
                $usuarioDB->save();
            }

            $user = Auth::user();



            //$sesionId = Session::getId() . "_" . $user->id;

            //$usuarios = Session::get('usuarios_sesion', []);
            $datosSesion = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                //'sesionId' => $sesionId,
            ];

            $usuarioJson = json_encode($datosSesion);
            //$usuarios[$sesionId] = $usuarioJson;
            //Session::put('usuarios_sesion', $usuarios);

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso.',
                'usuario' => $datosSesion
            ]);
            //return redirect()->route('muebles.index', ['sesionId' => $sesionId, 'usuario' => $user]);
        } else {
            $usuarioDB->intentos = ($usuarioDB->intentos ?? 0) + 1;

            if ($usuarioDB->intentos >= 3) {

                $usuarioDB->bloqueo_temporal = now()->addMinutes(5);
                $usuarioDB->intentos = 0;
                $usuarioDB->save();

                return response()->json([
                    'mensaje' => "Has superado el límite de intentos. Inténtalo dentro de 5 minutos."
                ], 401);
            }

            $usuarioDB->save();
            return response()->json([
                'mensaje' => "Credenciales incorrectas."
            ], 401);
        }
    }
    public function cerrarSesion(Request $request)
    {
        //todo: eliminar token de la bd

        $sesionId = $request->query('sesionId');
        //$usuarios = Session::get('usuarios_sesion', []);

        //if (isset($usuarios[$sesionId])) {
        //    unset($usuarios[$sesionId]);
        //    Session::put('usuarios_sesion', $usuarios);
        //}

        Auth::logout();
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();
        //REVISAR
        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.'
        ]);
    }
}
