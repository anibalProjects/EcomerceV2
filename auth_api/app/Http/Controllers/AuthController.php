<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Enum\RolUsuario;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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

            $user = $request->user();

            $token = Str::random(80);
            $user->access_token = $token;
            $user->save();

            //$sesionId = Session::getId() . "_" . $user->id;

            //$usuarios = Session::get('usuarios_sesion', []);
            $datosSesion = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'token' => $token,
                'rol' => $user->rol_id,
                //'sesionId' => $sesionId,
            ];

            $usuarioJson = json_encode($datosSesion);
            //$usuarios[$sesionId] = $usuarioJson;
            //Session::put('usuarios_sesion', $usuarios);

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso.',
                'usuario' => $datosSesion,
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
    
    public function create()
    {
        return view('registro');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);


        $existe = Usuario::where('email', $request->email)->first();

        if ($existe) {
            return back()->withErrors(['email' => "Ya has sido registrado con ese usaurio"]);
        }


        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->email_verified_at = Carbon::now();
        $usuario->remember_token = Str::random(10);
        $usuario->rol_id = 3;
        $usuario->bloqueo_temporal = null;
        $usuario->intentos = 0;
        $respUsuario = $usuario->save();



        return redirect()->route('muebles.index')->with('success', 'Usuario Cliente creado exitosamente.');
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

        if ($user = $request->user()) {
            $user->access_token = null;
            $user->save();
        }

        Auth::logout();
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();
        //REVISAR
        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.'
        ]);
    }
}
