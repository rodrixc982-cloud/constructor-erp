<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MovimientoCaja;
use App\Models\Presupuesto;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CajaController extends Controller implements HasMiddleware
{
    public function __construct()
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:caja.ver', only: ['index', 'datos']),
            new Middleware('can:caja.crear', only: ['store']),
            new Middleware('can:caja.editar', only: ['update']),
            new Middleware('can:caja.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();
        $presupuestos = Presupuesto::orderBy('codigo')->get();

        $saldo = MovimientoCaja::selectRaw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE -monto END) as saldo")->value('saldo') ?? 0;
        $ingresosMes = MovimientoCaja::where('tipo', 'ingreso')->whereMonth('fecha', now()->month)->sum('monto');
        $egresosMes = MovimientoCaja::where('tipo', 'egreso')->whereMonth('fecha', now()->month)->sum('monto');

        return view('caja.index', compact('clientes', 'proveedores', 'presupuestos', 'saldo', 'ingresosMes', 'egresosMes'));
    }

    public function datos(Request $request): JsonResponse
    {
        $movimientos = MovimientoCaja::with(['cliente', 'proveedor', 'usuario'])
            ->when($request->get('tipo'), function ($q, $tipo) {
                return $q->where('tipo', $tipo);
            })
            ->when($request->get('desde'), function ($q, $desde) {
                return $q->whereDate('fecha', '>=', $desde);
            })
            ->when($request->get('hasta'), function ($q, $hasta) {
                return $q->whereDate('fecha', '<=', $hasta);
            })
            ->when($request->get('cliente_id'), function ($q, $clienteId) {
                return $q->where('cliente_id', $clienteId);
            })
            ->when($request->get('proveedor_id'), function ($q, $proveedorId) {
                return $q->where('proveedor_id', $proveedorId);
            })
            ->when($request->get('buscar'), function ($q, $buscar) {
                return $q->where('concepto', 'LIKE', "%{$buscar}%")
                    ->orWhere('observaciones', 'LIKE', "%{$buscar}%");
            })
            ->latest('fecha')
            ->latest('id')
            ->get();

        return response()->json(['data' => $movimientos]);
    }

    /**
     * Mostrar un movimiento específico (para editar o ver detalles)
     */
    public function show(MovimientoCaja $movimientoCaja): JsonResponse
    {
        $movimientoCaja->load(['cliente', 'proveedor', 'usuario']);
        return response()->json(['data' => $movimientoCaja]);
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:ingreso,egreso'],
            'concepto' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
            'presupuesto_id' => ['nullable', 'exists:presupuestos,id'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $datos['usuario_id'] = $request->user()->id;
        $movimiento = MovimientoCaja::create($datos);

        return response()->json(['ok' => true, 'mensaje' => 'Movimiento registrado correctamente.', 'data' => $movimiento]);
    }

    public function update(Request $request, MovimientoCaja $movimientoCaja): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:ingreso,egreso'],
            'concepto' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
            'presupuesto_id' => ['nullable', 'exists:presupuestos,id'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $movimientoCaja->update($datos);

        return response()->json(['ok' => true, 'mensaje' => 'Movimiento actualizado correctamente.', 'data' => $movimientoCaja]);
    }

    public function destroy(MovimientoCaja $movimientoCaja): JsonResponse
    {
        $movimientoCaja->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Movimiento eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $movimiento = MovimientoCaja::withTrashed()->findOrFail($id);
        $movimiento->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Movimiento restaurado correctamente.']);
    }

    /**
     * Obtener resumen de caja
     */
    public function resumen(): JsonResponse
    {
        $saldo = MovimientoCaja::selectRaw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE -monto END) as saldo")->value('saldo') ?? 0;
        
        $ingresosHoy = MovimientoCaja::where('tipo', 'ingreso')->whereDate('fecha', now())->sum('monto');
        $egresosHoy = MovimientoCaja::where('tipo', 'egreso')->whereDate('fecha', now())->sum('monto');
        
        $ingresosMes = MovimientoCaja::where('tipo', 'ingreso')->whereMonth('fecha', now()->month)->sum('monto');
        $egresosMes = MovimientoCaja::where('tipo', 'egreso')->whereMonth('fecha', now()->month)->sum('monto');
        
        $ingresosAnio = MovimientoCaja::where('tipo', 'ingreso')->whereYear('fecha', now()->year)->sum('monto');
        $egresosAnio = MovimientoCaja::where('tipo', 'egreso')->whereYear('fecha', now()->year)->sum('monto');

        return response()->json([
            'data' => [
                'saldo' => $saldo,
                'ingresos_hoy' => $ingresosHoy,
                'egresos_hoy' => $egresosHoy,
                'ingresos_mes' => $ingresosMes,
                'egresos_mes' => $egresosMes,
                'ingresos_anio' => $ingresosAnio,
                'egresos_anio' => $egresosAnio,
            ]
        ]);
    }
}