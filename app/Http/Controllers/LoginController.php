<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Enum\RolUsuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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


        if (Auth::attempt($datos)) {
            $request->session()->regenerate();

            $user = Auth::user();

            $sesionId = Session::getId() . "_" . $user->id;

            $usuarios = Session::get('usuarios_sesion', []);
            $datosSesion = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'sessionId' => $sesionId
            ];

            $usuarioJson = json_encode($datosSesion);
            $usuarios[$sesionId] = $usuarioJson;
            Session::put('usuarios_sesion', $usuarios);

            return redirect()->route('pag_prueba', ['sessionId' => $sesionId]);
        }

        //return redirect()->route('pag_prueba', ['sessionId' => $sesionId]); //Redirige al pagina prueba


    }
    /*
    public function cerrarSesion(Request $request){

        $sesionId = $request->query('sessionId');
        $usuarios = Session::get('usuarios_sesion');

        if( isset($usuarios[$sesionId]) == true){
            unset($usuarios[$sesionId]);
            Session::put('usuarios_sesion', $usuarios);
        }
        return redirect()->route('login')->with('mensaje', 'Sesión cerrada correctamente.'); //Redirige al login
    }*/

}
