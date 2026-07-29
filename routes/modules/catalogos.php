<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ManoObraController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\MaquinariaController;
use Illuminate\Support\Facades\Route;

// --- Empresa (singleton) ---
Route::get('empresa', [EmpresaController::class, 'edit'])->name('empresa.edit');
Route::put('empresa', [EmpresaController::class, 'update'])->name('empresa.update');

// --- Clientes ---
Route::prefix('clientes')->name('clientes.')->group(function () {
    Route::get('/', [ClienteController::class, 'index'])->name('index');
    Route::get('/datos', [ClienteController::class, 'datos'])->name('datos');
    Route::get('/{cliente}', [ClienteController::class, 'show'])->name('show');
    Route::post('/', [ClienteController::class, 'store'])->name('store');
    Route::put('/{cliente}', [ClienteController::class, 'update'])->name('update');
    Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [ClienteController::class, 'restore'])->name('restore');
});

// --- Proveedores ---
Route::prefix('proveedores')->name('proveedores.')->group(function () {
    Route::get('/', [ProveedorController::class, 'index'])->name('index');
    Route::get('/datos', [ProveedorController::class, 'datos'])->name('datos');
    Route::get('/{proveedor}', [ProveedorController::class, 'show'])->name('show');
    Route::post('/', [ProveedorController::class, 'store'])->name('store');
    Route::put('/{proveedor}', [ProveedorController::class, 'update'])->name('update');
    Route::delete('/{proveedor}', [ProveedorController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [ProveedorController::class, 'restore'])->name('restore');
});

// --- Categorías ---
Route::prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/datos', [CategoriaController::class, 'datos'])->name('datos');
    Route::get('/{categoria}', [CategoriaController::class, 'show'])->name('show');
    Route::post('/', [CategoriaController::class, 'store'])->name('store');
    Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [CategoriaController::class, 'restore'])->name('restore');
});

// --- Materiales ---
Route::prefix('materiales')->name('materiales.')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])->name('index');
    Route::get('/datos', [MaterialController::class, 'datos'])->name('datos');
    Route::get('/{material}', [MaterialController::class, 'show'])->name('show');
    Route::post('/', [MaterialController::class, 'store'])->name('store');
    Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
    Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [MaterialController::class, 'restore'])->name('restore');
    Route::get('/{material}/qr', [MaterialController::class, 'qr'])->name('qr');
    Route::get('/exportar/excel', [MaterialController::class, 'exportarExcel'])->name('exportar.excel');
    Route::get('/exportar/pdf', [MaterialController::class, 'exportarPdf'])->name('exportar.pdf');
    Route::get('/importar/plantilla', [MaterialController::class, 'plantillaImportacion'])->name('importar.plantilla');
    Route::post('/importar', [MaterialController::class, 'importar'])->name('importar');
});

// --- Mano de Obra ---
Route::prefix('mano-obra')->name('mano-obra.')->group(function () {
    Route::get('/', [ManoObraController::class, 'index'])->name('index');
    Route::get('/datos', [ManoObraController::class, 'datos'])->name('datos');
    Route::get('/{mano_obra}', [ManoObraController::class, 'show'])->name('show');
    Route::post('/', [ManoObraController::class, 'store'])->name('store');
    Route::put('/{mano_obra}', [ManoObraController::class, 'update'])->name('update');
    Route::delete('/{mano_obra}', [ManoObraController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [ManoObraController::class, 'restore'])->name('restore');
});

// --- Equipos ---
Route::prefix('equipos')->name('equipos.')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('index');
    Route::get('/datos', [EquipoController::class, 'datos'])->name('datos');
    Route::get('/{equipo}', [EquipoController::class, 'show'])->name('show');
    Route::post('/', [EquipoController::class, 'store'])->name('store');
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [EquipoController::class, 'restore'])->name('restore');
});

// --- Maquinaria ---
Route::prefix('maquinaria')->name('maquinaria.')->group(function () {
    Route::get('/', [MaquinariaController::class, 'index'])->name('index');
    Route::get('/datos', [MaquinariaController::class, 'datos'])->name('datos');
    Route::get('/{maquinaria}', [MaquinariaController::class, 'show'])->name('show');
    Route::post('/', [MaquinariaController::class, 'store'])->name('store');
    Route::put('/{maquinaria}', [MaquinariaController::class, 'update'])->name('update');
    Route::delete('/{maquinaria}', [MaquinariaController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restaurar', [MaquinariaController::class, 'restore'])->name('restore');
});