<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\MuebleController;
use App\Models\Mueble;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('carrito', carritoController::class);

Route::resource('muebles', MuebleController::class);

Route::post('filtro', [MuebleController::class, 'filtrar'])->name('mueble.filtrar');
