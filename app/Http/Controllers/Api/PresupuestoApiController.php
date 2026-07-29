<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;

class PresupuestoApiController extends Controller
{
    public function index()
    {
        return Presupuesto::with(['cliente', 'obra'])->latest()->paginate(20)
            ->through(fn ($p) => array_merge($p->only(['id', 'codigo', 'estado', 'version', 'fecha']), ['total_general' => round($p->total_general, 2)]));
    }

    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load(['cliente', 'obra', 'partidas', 'gastos']);

        return array_merge($presupuesto->toArray(), ['totales' => $presupuesto->resumenTotales()]);
    }
}
