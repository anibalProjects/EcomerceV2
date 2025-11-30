<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\MuebleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {return view('welcome');})->name('welcome');

Route::resource('usuarios', RegisterController::class);

Route::post('usuarios/create', [RegisterController::class, 'crear'])->name('registro');

Route::get('/login', [LoginController::class, 'mostrar'])->name('login.mostrar');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'cerrarSesion'])->name('logout');


//Borrar todas las sesiones "temporal"
Route::get('/borrar_sesion', function () {
    return   Session::flush();
});

Route::get('/ver_sesion', function () {
    return   Session::all();
});
/*
Route::get('/', function () {
    $muebles = Mueble::all();
    $usuario = User::find(13);
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
*/
Route::resource('carrito', carritoController::class);

Route::resource('muebles', MuebleController::class);

Route::post('filtro', [MuebleController::class, 'filtrar'])->name('mueble.filtrar');
Route::post('/carrito/empty', [carritoController::class, 'empty'])->name('carrito.empty');
