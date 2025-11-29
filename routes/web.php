<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {return view('welcome');})->name('welcome');

Route::resource('carrito', carritoController::class);
Route::resource('usuarios', RegisterController::class);

Route::post('usuarios/create', [RegisterController::class, 'crear'])->name('registro');

Route::get('/login', [LoginController::class, 'mostrar'])->name('login.mostrar');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/pag_prueba', function () { return view('principal'); })->name('pag_prueba');

//Borrar todas las sesiones "temporal"
Route::get('/borrar_sesion', function () {
    return   Session::flush();
});

Route::get('/ver_sesion', function () {
    return   Session::all();
});
