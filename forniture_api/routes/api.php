<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuebleController;

// Agrupamos todo bajo el prefijo 'v1' 
Route::prefix('v1')->group(function () {

    // --- RUTAS PÚBLICAS ---
    Route::get('/muebles', [MuebleController::class, 'index']);
    Route::get('/muebles/carrito', [MuebleController::class, 'showByIds']);
    Route::get('/muebles/{id}', [MuebleController::class, 'show']);

    // --- RUTAS PROTEGIDAS ---
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/muebles', [MuebleController::class, 'store']);
        Route::put('/muebles/{id}', [MuebleController::class, 'update']);
        Route::delete('/muebles/{id}', [MuebleController::class, 'destroy']);

    });
});
