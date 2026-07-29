<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\MovimientoCaja;
use App\Models\Obra;
use App\Models\Presupuesto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('dashboard.stats', now()->addMinutes(5), function () {
            return [
                'obras_activas' => Obra::where('estado', 'activa')->count(),
                'obras_terminadas' => Obra::where('estado', 'terminada')->count(),
                'clientes' => Cliente::activos()->count(),
                'proveedores' => Proveedor::activos()->count(),
                'presupuestos' => Presupuesto::count(),
                'materiales_stock_bajo' => Material::stockBajo()->count(),
                'ganancias_mes' => MovimientoCaja::where('tipo', 'ingreso')->whereMonth('fecha', now()->month)->sum('monto'),
                'gastos_mes' => MovimientoCaja::where('tipo', 'egreso')->whereMonth('fecha', now()->month)->sum('monto'),
            ];
        });
        $stats['utilidad_mes'] = $stats['ganancias_mes'] - $stats['gastos_mes'];

        $graficoUtilidadMensual = Cache::remember('dashboard.grafico', now()->addMinutes(5), function () {
            $meses = collect(range(1, 6))->map(fn ($i) => now()->subMonths(6 - $i));

            return [
                'labels' => $meses->map(fn ($m) => $m->translatedFormat('M'))->toArray(),
                'data' => $meses->map(function ($m) {
                    $ingresos = MovimientoCaja::where('tipo', 'ingreso')->whereMonth('fecha', $m->month)->whereYear('fecha', $m->year)->sum('monto');
                    $egresos = MovimientoCaja::where('tipo', 'egreso')->whereMonth('fecha', $m->month)->whereYear('fecha', $m->year)->sum('monto');

                    return round($ingresos - $egresos, 2);
                })->toArray(),
            ];
        });

        return view('dashboard.index', compact('stats', 'graficoUtilidadMensual'));
    }
}
