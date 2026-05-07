<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CookieMoneda;
use App\Http\Controllers\CookiePaginacion;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');

Route::post('/guardar-tema', [CookiePersonalizacion::class, 'guardarTema'])->name('preferencias.tema.guardar');
Route::post('/guardar-moneda', [CookieMoneda::class, 'guardarMoneda'])->name('preferencias.moneda.guardar');
Route::post('/guardar-paginacion', [CookiePaginacion::class, 'guardarPaginacion'])->name('preferencias.paginacion.guardar');
