<?php

use App\Http\Controllers\CookieMoneda;
use App\Http\Controllers\CookiePaginacion;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\MuebleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\MueblesAdministracionController;
use App\Http\Controllers\CategoriasAdministracionController;

Route::get('/', function () {
    return redirect()->route('muebles.index');
})->name('home');

Route::resource('usuarios', RegisterController::class);


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


Route::get('/imagen/{nombre}', [MuebleController::class, 'mostrarGaleria'])->name('imagen.mostrar');
Route::get('imagen/muebles/{path}', [MuebleController::class, 'showMueble'])
    ->where('path', '.*')
    ->name('imagen.mueble');

        Route::get('/muebles', [MuebleController::class, 'index'])->name('muebles.index');
        Route::get('/muebles/{mueble}', [MuebleController::class, 'show'])->name('muebles.show');
        Route::get('filtro', [MuebleController::class, 'filtrar'])->name('mueble.filtrar');
        Route::resource('carrito', carritoController::class);
        Route::post('/carrito/buy',          [carritoController::class, 'buy'])->name('carrito.buy');
        Route::get('/carrito/returnFromBuy', [carritoController::class, 'returnFromBuy'])->name('carrito.returnFromBuy');
        Route::post('/carrito/empty',        [carritoController::class, 'empty'])->name('carrito.empty');
        Route::get('/perfil', [LoginController::class, 'perfil'])->name('perfil.show');
        Route::get('/preferencias/{userId}',        [CookiePersonalizacion::class, 'index'])->name('preferencias.index');
        Route::post('/preferencias/{userId}/update',[CookiePersonalizacion::class, 'update'])->name('preferencias.update');

    Route::post('/guardar-tema',      [CookiePersonalizacion::class, 'guardarTema'])->name('preferencias.tema.guardar');
    Route::post('/guardar-moneda',    [CookieMoneda::class, 'guardarMoneda'])->name('preferencias.moneda.guardar');
    Route::post('/guardar-paginacion',[CookiePaginacion::class, 'guardarPaginacion'])->name('preferencias.paginacion.guardar');

// ─── Panel de Administración (solo rol 1: admin.panel) ───────────────────────
Route::middleware(['auth.api', 'check.ability:admin.panel'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('muebles', MueblesAdministracionController::class);

        // Rutas de la galeria
        Route::get('muebles/{id}/galeria',           [MueblesAdministracionController::class, 'galeria'])->name('muebles.galeria');
        Route::post('muebles/{id}/galeria',          [MueblesAdministracionController::class, 'uploadGaleria'])->name('muebles.galeria.upload');
        Route::post('galeria/{id}/principal',        [MueblesAdministracionController::class, 'setPrincipalGaleria'])->name('muebles.galeria.principal');
        Route::delete('galeria/{id}',                [MueblesAdministracionController::class, 'deleteImagenGaleria'])->name('muebles.galeria.delete');

        // Rutas de categorías
        Route::resource('categorias', CategoriasAdministracionController::class);
    });

