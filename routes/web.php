<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Models\Mueble;
use App\Models\User;

Route::get('/', function () {
    $muebles = Mueble::all();
    $usuario = User::find(1);
    $sesionId = Session::getId() . '_' . $usuario->id;
     $usuarios = Session::get('usuarios_sesion', []);
            $datosSesion = [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'sessionId' => $sesionId
            ];
    $usuarioJson = json_encode($datosSesion);
    $usuarios[$sesionId] = $usuarioJson;
    Session::put('usuarios_sesion', $usuarios);
    return view('vistaprueba',compact('muebles', 'sesionId'));
});

Route::resource('carrito', carritoController::class);
Route::post('/carrito/empty', [carritoController::class, 'empty'])->name('carrito.empty');
