<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CalculadoraController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

// --- Calculadora inteligente (Fase 6) ---
Route::prefix('calculadora')->name('calculadora.')->group(function () {
    Route::get('/', [CalculadoraController::class, 'index'])->name('index');
    Route::post('/muro', [CalculadoraController::class, 'muro'])->name('muro');
    Route::post('/concreto', [CalculadoraController::class, 'concreto'])->name('concreto');
    Route::post('/acero', [CalculadoraController::class, 'acero'])->name('acero');
    Route::post('/pintura', [CalculadoraController::class, 'pintura'])->name('pintura');
    Route::post('/ceramica', [CalculadoraController::class, 'ceramica'])->name('ceramica');
    Route::post('/completa', [CalculadoraController::class, 'construccionCompleta'])->name('completa');
});

// --- Compras (Fase 7) ---
Route::prefix('compras')->name('compras.')->group(function () {
    Route::get('/', [CompraController::class, 'index'])->name('index');
    Route::get('/datos', [CompraController::class, 'datos'])->name('datos');
    Route::post('/', [CompraController::class, 'store'])->name('store');
    Route::post('/generar-desde-presupuesto/{presupuesto}', [CompraController::class, 'generarDesdePresupuesto'])->name('generar-desde-presupuesto');
    Route::put('/{orden_compra}/estado', [CompraController::class, 'cambiarEstado'])->name('estado');
});

// --- Caja (Fase 7) ---
Route::prefix('caja')->name('caja.')->group(function () {
    Route::get('/', [CajaController::class, 'index'])->name('index');
    Route::get('/datos', [CajaController::class, 'datos'])->name('datos');
    Route::post('/', [CajaController::class, 'store'])->name('store');
});

// --- Facturación (Fase 7) ---
Route::prefix('facturacion')->name('facturacion.')->group(function () {
    Route::get('/', [FacturacionController::class, 'index'])->name('index');
    Route::get('/datos', [FacturacionController::class, 'datos'])->name('datos');
    Route::post('/', [FacturacionController::class, 'store'])->name('store');
    Route::post('/{documento_venta}/anular', [FacturacionController::class, 'anular'])->name('anular');
    Route::get('/{documento_venta}/exportar/pdf', [FacturacionController::class, 'exportarPdf'])->name('exportar.pdf');
});

// --- Calendario (Fase 8) ---
Route::prefix('calendario')->name('calendario.')->group(function () {
    Route::get('/', [CalendarioController::class, 'index'])->name('index');
    Route::get('/datos', [CalendarioController::class, 'datos'])->name('datos');
    Route::post('/', [CalendarioController::class, 'store'])->name('store');
    Route::put('/{evento}', [CalendarioController::class, 'update'])->name('update');
    Route::delete('/{evento}', [CalendarioController::class, 'destroy'])->name('destroy');
});

// --- Reportes (Fase 8) ---
Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('/por-cliente', [ReporteController::class, 'porCliente'])->name('por-cliente');
    Route::get('/por-proyecto', [ReporteController::class, 'porProyecto'])->name('por-proyecto');
    Route::get('/por-material', [ReporteController::class, 'porMaterial'])->name('por-material');
    Route::get('/utilidad-mensual', [ReporteController::class, 'utilidadPorMes'])->name('utilidad-mensual');
    Route::get('/por-estado', [ReporteController::class, 'porEstadoPresupuesto'])->name('por-estado');
});

// --- Auditoría (Fase 8) ---
Route::prefix('auditoria')->name('auditoria.')->group(function () {
    Route::get('/', [AuditoriaController::class, 'index'])->name('index');
    Route::get('/datos', [AuditoriaController::class, 'datos'])->name('datos');
});

// --- Notificaciones (Fase 8) ---
Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
    Route::get('/', [NotificacionController::class, 'index'])->name('index');
    Route::get('/datos', [NotificacionController::class, 'datos'])->name('datos');
    Route::post('/{id}/leer', [NotificacionController::class, 'marcarLeida'])->name('leer');
    Route::post('/marcar-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('marcar-todas');
});

// --- Configuración (Fase 9) ---
Route::prefix('configuracion')->name('configuracion.')->group(function () {
    Route::get('/', [ConfiguracionController::class, 'edit'])->name('edit');
    Route::put('/', [ConfiguracionController::class, 'update'])->name('update');
});
