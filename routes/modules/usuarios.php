<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('index');
    Route::get('/datos', [UsuarioController::class, 'datos'])->name('datos');
    Route::get('/{user}', [UsuarioController::class, 'show'])->name('show');
    Route::post('/', [UsuarioController::class, 'store'])->name('store');
    Route::put('/{user}', [UsuarioController::class, 'update'])->name('update');
    Route::delete('/{user}', [UsuarioController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [UsuarioController::class, 'restore'])->name('restore');
});