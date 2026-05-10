<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CookieMoneda;
use App\Http\Controllers\CookiePaginacion;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');
	Route::get('/validate-token', [AuthController::class, 'validateToken']);
	Route::get('/perfil', [AuthController::class, 'perfil'])->middleware('abilities:perfil.ver');
});
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');

Route::post('/guardar-tema', [CookiePersonalizacion::class, 'guardarTema'])->name('preferencias.tema.guardar');
Route::post('/guardar-moneda', [CookieMoneda::class, 'guardarMoneda'])->name('preferencias.moneda.guardar');
Route::post('/guardar-paginacion', [CookiePaginacion::class, 'guardarPaginacion'])->name('preferencias.paginacion.guardar');
