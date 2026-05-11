<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuebleController;

    Route::get('/muebles',      [MuebleController::class, 'index']);
    Route::get('/muebles/{id}', [MuebleController::class, 'show']);

// ─── muebles.crear ───────────────────────────────────────────────────────────
Route::middleware('check.abilities:muebles.crear')->group(function () {
    Route::post('/muebles', [MuebleController::class, 'store']);
});

// ─── muebles.editar ──────────────────────────────────────────────────────────
Route::middleware('check.abilities:muebles.editar')->group(function () {
    Route::put('/muebles/{id}',   [MuebleController::class, 'update']);
    Route::patch('/muebles/{id}', [MuebleController::class, 'update']);
});

// ─── muebles.eliminar ────────────────────────────────────────────────────────
Route::middleware('check.abilities:muebles.eliminar')->group(function () {
    Route::delete('/muebles/{id}', [MuebleController::class, 'destroy']);
});
