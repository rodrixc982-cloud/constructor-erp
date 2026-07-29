<?php

use App\Http\Controllers\AuditoriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('auditoria')->name('auditoria.')->group(function () {
    Route::get('/', [AuditoriaController::class, 'index'])->name('index');
    Route::get('/datos', [AuditoriaController::class, 'datos'])->name('datos');
    Route::get('/estadisticas', [AuditoriaController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/{id}', [AuditoriaController::class, 'show'])->name('show'); // <-- ESTA LÍNEA
    Route::delete('/limpiar', [AuditoriaController::class, 'limpiar'])->name('limpiar');
});