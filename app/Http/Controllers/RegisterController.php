<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

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

        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->email_verified_at = now();
        $usuario->remember_token = Str::random(10);
        $usuario->rol_id = 3;
        $usuario->bloqueo_temporal = null;
        $usuario->intentos = 0;
        $respUsuario = $usuario->save();



        return redirect()->route('muebles.index')->with('success', 'Usuario Cliente creado exitosamente.');
    }
}
