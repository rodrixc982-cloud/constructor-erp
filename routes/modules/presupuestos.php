<?php

use App\Http\Controllers\ApuController;
use App\Http\Controllers\PresupuestoController;
use Illuminate\Support\Facades\Route;

Route::prefix('apu')->name('apu.')->group(function () {
    Route::get('/', [ApuController::class, 'index'])->name('index');
    Route::get('/datos', [ApuController::class, 'datos'])->name('datos');
    Route::get('/{apu}', [ApuController::class, 'show'])->name('show');
    Route::post('/', [ApuController::class, 'store'])->name('store');
    Route::put('/{apu}', [ApuController::class, 'update'])->name('update');
    Route::delete('/{apu}', [ApuController::class, 'destroy'])->name('destroy');
});

Route::prefix('presupuestos')->name('presupuestos.')->group(function () {
    Route::get('/', [PresupuestoController::class, 'index'])->name('index');
    Route::get('/datos', [PresupuestoController::class, 'datos'])->name('datos');
    Route::get('/{presupuesto}/editar', [PresupuestoController::class, 'edit'])->name('edit');
    Route::get('/{presupuesto}', [PresupuestoController::class, 'show'])->name('show');
    Route::post('/', [PresupuestoController::class, 'store'])->name('store');
    Route::put('/{presupuesto}', [PresupuestoController::class, 'update'])->name('update');

    Route::post('/{presupuesto}/partidas/apu', [PresupuestoController::class, 'agregarPartidaApu'])->name('partidas.apu');
    Route::post('/{presupuesto}/partidas/manual', [PresupuestoController::class, 'agregarPartidaManual'])->name('partidas.manual');
    Route::put('/partidas/{partida}', [PresupuestoController::class, 'actualizarPartida'])->name('partidas.update');
    Route::delete('/partidas/{partida}', [PresupuestoController::class, 'eliminarPartida'])->name('partidas.destroy');

    Route::post('/{presupuesto}/gastos', [PresupuestoController::class, 'agregarGasto'])->name('gastos.store');
    Route::put('/gastos/{gasto}', [PresupuestoController::class, 'actualizarGasto'])->name('gastos.update');
    Route::delete('/gastos/{gasto}', [PresupuestoController::class, 'eliminarGasto'])->name('gastos.destroy');

    Route::post('/{presupuesto}/duplicar', [PresupuestoController::class, 'duplicar'])->name('duplicar');
    Route::post('/{presupuesto}/nueva-version', [PresupuestoController::class, 'nuevaVersion'])->name('nueva-version');
    Route::post('/{presupuesto}/aprobar', [PresupuestoController::class, 'aprobar'])->name('aprobar');
    Route::post('/{presupuesto}/rechazar', [PresupuestoController::class, 'rechazar'])->name('rechazar');
    Route::post('/{presupuesto}/archivar', [PresupuestoController::class, 'archivar'])->name('archivar');
    Route::get('/{presupuesto}/exportar/pdf', [PresupuestoController::class, 'exportarPdf'])->name('exportar.pdf');
});
