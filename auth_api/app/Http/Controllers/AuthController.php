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

            if ($usuarioDB) {
                $usuarioDB->intentos = 0;
                $usuarioDB->bloqueo_temporal = null;
                $usuarioDB->save();
            }

            $user = $request->user();
            $abilities = $this->abilitiesForRole($user->rol_id);
            $token = $user->createToken('auth_token', $abilities)->plainTextToken;

            $datosSesion = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'token' => $token,
                'rol' => $user->rol_id,
                'abilities' => $abilities,
            ];

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso.',
                'usuario' => $datosSesion,
            ]);
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
            return response()->json([
                'mensaje' => 'El email ya está registrado.'
            ], 409);
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



        return response()->json([
            'mensaje' => 'Usuario registrado exitosamente.',
            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'email' => $usuario->email,
            ]
        ], 201);
    }
    public function cerrarSesion(Request $request)
    {
        if ($user = $request->user()) {
            $currentToken = $user->currentAccessToken();
            if ($currentToken) {
                $currentToken->delete();
            }
        }

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.'
        ]);
    }

    /**
     * Get default token abilities for a role.
     */
    private function abilitiesForRole(int $rolId): array
    {
        return match ($rolId) {
            1 => [
                'admin:read',
                'admin:create',
                'admin:update',
                'admin:delete',
            ],
            3 => [
                'web:view',
                'web:buy',
                'web:profile:update',
            ],
            default => [
                'web:view',
            ],
        };
    }

    /**
     * Valida un token y comprueba si tiene una habilidad específica.
     */
    public function validateToken(Request $request)
    {
        $ability = $request->query('ability');

        // Si se solicita una habilidad específica, comprobamos si el token la tiene
        if ($ability && !$request->user()->tokenCan($ability)) {
            return response()->json([
                'valido' => false,
                'mensaje' => "No tiene el permiso necesario para: {$ability}"
            ], 403);
        }

        return response()->json([
            'valido' => true,
            'usuario' => [
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'rol_id' => $request->user()->rol_id,
            ],
            'abilities' => $request->user()->currentAccessToken()->abilities
        ]);
    }

    /**
     * Devuelve los datos del usuario autenticado vía token.
     */
    public function perfil(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'       => $user->id,
            'nombre'   => $user->nombre,
            'apellido' => $user->apellido,
            'email'    => $user->email,
            'rol_id'   => $user->rol_id,
            'abilities' => $user->currentAccessToken()->abilities,
        ]);
    }
}
