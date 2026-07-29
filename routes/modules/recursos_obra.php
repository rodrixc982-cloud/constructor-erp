<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ManoObraController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\ObraController;
use Illuminate\Support\Facades\Route;

Route::prefix('mano-obra')->name('mano-obra.')->group(function () {
    Route::get('/', [ManoObraController::class, 'index'])->name('index');
    Route::get('/datos', [ManoObraController::class, 'datos'])->name('datos');
    Route::post('/', [ManoObraController::class, 'store'])->name('store');
    Route::put('/{mano_obra}', [ManoObraController::class, 'update'])->name('update');
    Route::delete('/{mano_obra}', [ManoObraController::class, 'destroy'])->name('destroy');
});

Route::prefix('equipos')->name('equipos.')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('index');
    Route::get('/datos', [EquipoController::class, 'datos'])->name('datos');
    Route::post('/', [EquipoController::class, 'store'])->name('store');
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
});

Route::prefix('maquinaria')->name('maquinaria.')->group(function () {
    Route::get('/', [MaquinariaController::class, 'index'])->name('index');
    Route::get('/datos', [MaquinariaController::class, 'datos'])->name('datos');
    Route::post('/', [MaquinariaController::class, 'store'])->name('store');
    Route::put('/{maquinaria}', [MaquinariaController::class, 'update'])->name('update');
    Route::delete('/{maquinaria}', [MaquinariaController::class, 'destroy'])->name('destroy');
});

Route::prefix('obras')->name('obras.')->group(function () {
    Route::get('/', [ObraController::class, 'index'])->name('index');
    Route::get('/datos', [ObraController::class, 'datos'])->name('datos');
    Route::get('/{obra}', [ObraController::class, 'show'])->name('show');
    Route::post('/', [ObraController::class, 'store'])->name('store');
    Route::put('/{obra}', [ObraController::class, 'update'])->name('update');
    Route::delete('/{obra}', [ObraController::class, 'destroy'])->name('destroy');
    Route::post('/{obra}/adjuntos', [ObraController::class, 'subirAdjunto'])->name('adjuntos.subir');
    Route::delete('/adjuntos/{adjunto}', [ObraController::class, 'eliminarAdjunto'])->name('adjuntos.eliminar');
});
