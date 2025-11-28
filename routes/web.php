<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\MuebleController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('carrito', carritoController::class);

Route::resource('muebles', MuebleController::class);
