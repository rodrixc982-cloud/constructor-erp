<?php

use App\Http\Controllers\ObraController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('obras')->name('obras.')->group(function () {
    Route::get('/', [ObraController::class, 'index'])->name('index');
    Route::get('/datos', [ObraController::class, 'datos'])->name('datos');
    Route::get('/{obra}', [ObraController::class, 'show'])->name('show');
    Route::get('/{obra}/ver', [ObraController::class, 'ver'])->name('ver');
    Route::post('/', [ObraController::class, 'store'])->name('store');
    Route::put('/{obra}', [ObraController::class, 'update'])->name('update');
    Route::delete('/{obra}', [ObraController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [ObraController::class, 'restore'])->name('restore');
    Route::post('/{obra}/adjuntos', [ObraController::class, 'subirAdjunto'])->name('adjuntos.subir');
    Route::delete('/adjuntos/{adjunto}', [ObraController::class, 'eliminarAdjunto'])->name('adjuntos.eliminar');
});