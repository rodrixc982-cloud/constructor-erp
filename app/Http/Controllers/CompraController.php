<?php

namespace App\Http\Controllers;

use App\Models\OrdenCompra;
use App\Models\Presupuesto;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompraController extends Controller implements HasMiddleware
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
            new Middleware('can:compras.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:compras.crear', only: ['store', 'generarDesdePresupuesto']),
            new Middleware('can:compras.editar', only: ['update', 'cambiarEstado']),
            new Middleware('can:compras.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();
        $presupuestos = Presupuesto::orderBy('codigo')->get();

        return view('compras.index', compact('proveedores', 'presupuestos'));
    }

    public function datos(Request $request): JsonResponse
    {
        $ordenes = OrdenCompra::with(['proveedor', 'items'])
            ->when($request->get('estado'), function ($q, $estado) {
                return $q->where('estado', $estado);
            })
            ->when($request->get('proveedor_id'), function ($q, $proveedorId) {
                return $q->where('proveedor_id', $proveedorId);
            })
            ->when($request->get('desde'), function ($q, $desde) {
                return $q->whereDate('fecha', '>=', $desde);
            })
            ->when($request->get('hasta'), function ($q, $hasta) {
                return $q->whereDate('fecha', '<=', $hasta);
            })
            ->latest()
            ->get()
            ->map(fn ($o) => array_merge($o->only(['id', 'codigo', 'fecha', 'estado']), [
                'proveedor' => $o->proveedor,
                'total' => round($o->total ?? $o->items->sum('subtotal'), 2),
                'items_count' => $o->items->count(),
            ]));

        return response()->json(['data' => $ordenes]);
    }

    /**
     * Mostrar una orden de compra específica
     */
    public function show(OrdenCompra $orden_compra): JsonResponse
    {
        $orden_compra->load(['proveedor', 'items.material', 'usuario', 'presupuesto']);
        return response()->json(['data' => $orden_compra]);
    }

    protected function siguienteCodigo(): string
    {
        $ultimo = OrdenCompra::withTrashed()->orderByDesc('id')->first();
        $numero = ($ultimo?->id ?? 0) + 1;
        return 'OC-' . date('Y') . '-' . str_pad((string) $numero, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'fecha' => ['required', 'date'],
            'observaciones' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materiales,id'],
            'items.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'presupuesto_id' => ['nullable', 'exists:presupuestos,id'],
        ]);

        $orden = DB::transaction(function () use ($datos, $request) {
            $orden = OrdenCompra::create([
                'codigo' => $this->siguienteCodigo(),
                'proveedor_id' => $datos['proveedor_id'],
                'fecha' => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
                'presupuesto_id' => $datos['presupuesto_id'] ?? null,
                'usuario_id' => $request->user()->id,
                'estado' => 'pendiente',
            ]);

            foreach ($datos['items'] as $item) {
                $orden->items()->create($item);
            }

            return $orden;
        });

        return response()->json([
            'ok' => true,
            'mensaje' => 'Orden de compra creada correctamente.',
            'data' => $orden->load('items')
        ]);
    }

    public function update(Request $request, OrdenCompra $orden_compra): JsonResponse
    {
        $datos = $request->validate([
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'fecha' => ['required', 'date'],
            'observaciones' => ['nullable', 'string'],
            'estado' => ['nullable', 'in:pendiente,aprobada,recibida,cancelada'],
        ]);

        $orden_compra->update($datos);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Orden de compra actualizada correctamente.',
            'data' => $orden_compra
        ]);
    }

    public function cambiarEstado(Request $request, OrdenCompra $orden_compra): JsonResponse
    {
        $datos = $request->validate([
            'estado' => ['required', 'in:pendiente,aprobada,recibida,cancelada']
        ]);

        $orden_compra->update($datos);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Estado actualizado correctamente a "' . $datos['estado'] . '".'
        ]);
    }

    public function destroy(OrdenCompra $orden_compra): JsonResponse
    {
        $orden_compra->delete();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Orden de compra eliminada correctamente.'
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $orden = OrdenCompra::withTrashed()->findOrFail($id);
        $orden->restore();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Orden de compra restaurada correctamente.'
        ]);
    }

    /**
     * Genera automáticamente una orden de compra por proveedor con
     * los materiales requeridos en las partidas de un presupuesto.
     */
    public function generarDesdePresupuesto(Request $request, Presupuesto $presupuesto): JsonResponse
    {
        $materialesPorProveedor = $presupuesto->partidas()
            ->with('apu.materiales.material.proveedor')
            ->get()
            ->flatMap(fn ($partida) => $partida->apu?->materiales ?? collect())
            ->filter(fn ($m) => $m->material?->proveedor_id)
            ->groupBy('material.proveedor_id');

        if ($materialesPorProveedor->isEmpty()) {
            return response()->json([
                'ok' => false,
                'message' => 'No se encontraron materiales con proveedor asignado en este presupuesto.'
            ], 422);
        }

        $ordenesCreadas = DB::transaction(function () use ($materialesPorProveedor, $presupuesto, $request) {
            $ordenes = [];
            foreach ($materialesPorProveedor as $proveedorId => $lineas) {
                $orden = OrdenCompra::create([
                    'codigo' => $this->siguienteCodigo(),
                    'proveedor_id' => $proveedorId,
                    'presupuesto_id' => $presupuesto->id,
                    'fecha' => now(),
                    'observaciones' => 'Generada automáticamente desde el presupuesto ' . $presupuesto->codigo,
                    'usuario_id' => $request->user()->id,
                    'estado' => 'pendiente',
                ]);

                foreach ($lineas->groupBy('material_id') as $materialId => $grupo) {
                    $orden->items()->create([
                        'material_id' => $materialId,
                        'cantidad' => $grupo->sum('cantidad'),
                        'precio_unitario' => $grupo->first()->precio_unitario ?? 0,
                    ]);
                }

                $ordenes[] = $orden;
            }

            return $ordenes;
        });

        return response()->json([
            'ok' => true,
            'mensaje' => count($ordenesCreadas) . ' orden(es) de compra generada(s).',
            'data' => $ordenesCreadas
        ]);
    }
}