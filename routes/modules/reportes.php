<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('/por-cliente', [ReporteController::class, 'porCliente'])->name('por-cliente');
    Route::get('/por-proyecto', [ReporteController::class, 'porProyecto'])->name('por-proyecto');
    Route::get('/por-material', [ReporteController::class, 'porMaterial'])->name('por-material');
    Route::get('/utilidad-mensual', [ReporteController::class, 'utilidadPorMes'])->name('utilidad-mensual');
    Route::get('/por-estado', [ReporteController::class, 'porEstadoPresupuesto'])->name('por-estado');
    Route::get('/datos', [ReporteController::class, 'datosReporte'])->name('datos');
});