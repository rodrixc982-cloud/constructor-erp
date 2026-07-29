<?php

use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventario')->name('inventario.')->group(function () {
    Route::get('/', [InventarioController::class, 'index'])->name('index');
    Route::get('/datos', [InventarioController::class, 'datos'])->name('datos');
    Route::get('/kardex/{material}', [InventarioController::class, 'kardex'])->name('kardex');
    Route::post('/entrada', [InventarioController::class, 'entrada'])->name('entrada');
    Route::post('/salida', [InventarioController::class, 'salida'])->name('salida');
    Route::post('/transferencia', [InventarioController::class, 'transferencia'])->name('transferencia');
    Route::post('/ajuste', [InventarioController::class, 'ajuste'])->name('ajuste');
    Route::post('/almacenes', [InventarioController::class, 'guardarAlmacen'])->name('almacen.guardar');
});
