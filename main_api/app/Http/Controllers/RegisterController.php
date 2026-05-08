<?php

namespace App\Http\Controllers;

use App\Services\AuthApiService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function create()
    {
        return view('registro');
    }

    public function store(Request $request, AuthApiService $authService)
    {
        // Validaciones previas
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Llamada al microservicio de autenticación
        $respuesta = $authService->registrar($datos);

        if ($respuesta['estado'] === 201) {
            return redirect()->route('login.mostrar')->with('mensaje', 'Usuario registrado exitosamente. Ya puedes iniciar sesión.');
        }

        // Manejo de errores de la API (ej: email duplicado)
        $mensajeError = $respuesta['datos']['mensaje'] ?? 'Error al registrar el usuario';
        return back()->withInput()->withErrors(['email' => $mensajeError]);
    }
}
