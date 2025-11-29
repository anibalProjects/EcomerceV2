<?php

use App\Http\Controllers\CookieMoneda;
use App\Http\Controllers\CookiePaginacion;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Es necesario tener un sistema de autenticación (ej. Laravel Breeze o UI)
// para que las rutas de login, registro y logout estén disponibles.
// Auth::routes(); // Si usas laravel/ui

// Rutas para probar las preferencias de usuario
Route::middleware('auth')->group(function () {
    // Ruta para mostrar la página de prueba
    Route::get('/preferencias', function () {
        return view('pruebaIndex');
    })->name('preferencias.index');

    // Rutas para guardar las preferencias
    Route::post('/guardar-tema', [CookiePersonalizacion::class, 'guardarTema'])->name('preferencias.tema.guardar');
    Route::post('/guardar-moneda', [CookieMoneda::class, 'guardarMoneda'])->name('preferencias.moneda.guardar');
    Route::post('/guardar-paginacion', [CookiePaginacion::class, 'guardarPaginacion'])->name('preferencias.paginacion.guardar');
});

// Incluye las rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
