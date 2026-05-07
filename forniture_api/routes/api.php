<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuebleController;

// Rutas de Muebles
Route::get('/muebles', [MuebleController::class, 'index']);
// Route::post('/muebles', [MuebleController::class, 'store']);
