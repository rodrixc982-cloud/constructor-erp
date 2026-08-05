<?php
// routes/modules/catalogos.php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('catalogos')->name('catalogos.')->group(function () {
    // ... rutas existentes de catálogos ...
});

// ===== AGREGAR RUTAS DE CATEGORÍAS AQUÍ =====
Route::middleware(['auth'])->prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/datos', [CategoriaController::class, 'datos'])->name('datos');
    Route::get('/{id}', [CategoriaController::class, 'show'])->name('show');
    Route::post('/', [CategoriaController::class, 'store'])->name('store');
    Route::put('/{id}', [CategoriaController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-estado', [CategoriaController::class, 'toggleEstado'])->name('toggle-estado');
});