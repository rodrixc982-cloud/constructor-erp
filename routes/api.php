<?php

use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\MaterialApiController;
use App\Http\Controllers\Api\PresupuestoApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * API REST v1, protegida con Sanctum (Bearer token). Para generar un
 * token: $user->createToken('nombre-app')->plainTextToken.
 * Documentación completa pendiente de publicar (ej. vía Scramble o
 * L5-Swagger) cuando el catálogo de endpoints esté cerrado.
 */
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('clientes', ClienteApiController::class)->only(['index', 'show', 'store']);
    Route::apiResource('materiales', MaterialApiController::class)->only(['index', 'show']);
    Route::apiResource('presupuestos', PresupuestoApiController::class)->only(['index', 'show']);
});
