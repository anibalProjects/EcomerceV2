<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('carrito', carritoController::class);
