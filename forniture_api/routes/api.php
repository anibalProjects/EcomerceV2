<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuebleController;
use App\Http\Controllers\CategoriaController;

// ─── Rutas públicas de muebles ────────────────────────────────────────────────
Route::get('/muebles',           [MuebleController::class, 'index']);
Route::get('/muebles/{id}',      [MuebleController::class, 'show']);
Route::get('/muebles-lista',     [MuebleController::class, 'showByIds']);
Route::post('/muebles/{id}/reduce-stock', [MuebleController::class, 'reduceStock']);

// ─── Rutas públicas de categorías ────────────────────────────────────────────
Route::get('/categorias',        [CategoriaController::class, 'index']);
Route::get('/categorias/{id}',   [CategoriaController::class, 'show']);

// ─── muebles.crear (Admin o Gestor) ──────────────────────────────────────────
// El middleware acepta abilities separadas por coma (cualquiera es válida)
Route::middleware('check.abilities:muebles.crear,gestor.muebles.crear')->group(function () {
    Route::post('/muebles',          [MuebleController::class, 'store']);
    Route::post('/categorias',       [CategoriaController::class, 'store']);
});

// ─── muebles.editar (Admin o Gestor) ─────────────────────────────────────────
Route::middleware('check.abilities:muebles.editar,gestor.muebles.editar')->group(function () {
    Route::put('/muebles/{id}',      [MuebleController::class, 'update']);
    Route::patch('/muebles/{id}',    [MuebleController::class, 'update']);
    Route::put('/categorias/{id}',   [CategoriaController::class, 'update']);
    Route::patch('/categorias/{id}', [CategoriaController::class, 'update']);
});

// ─── muebles.eliminar (Admin o Gestor) ───────────────────────────────────────
Route::middleware('check.abilities:muebles.eliminar,gestor.muebles.eliminar')->group(function () {
    Route::delete('/muebles/{id}',      [MuebleController::class, 'destroy']);
    Route::delete('/categorias/{id}',   [CategoriaController::class, 'destroy']);
});
