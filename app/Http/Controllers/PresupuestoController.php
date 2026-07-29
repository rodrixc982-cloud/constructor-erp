<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Obra;
use App\Models\Presupuesto;
use App\Models\PresupuestoGasto;
use App\Models\PresupuestoPartida;
use App\Models\User;
use App\Services\PresupuestoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PresupuestoController extends Controller implements HasMiddleware
{
    public function __construct(protected PresupuestoService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:presupuestos.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:presupuestos.crear', only: ['store', 'duplicar', 'nuevaVersion']),
            new Middleware('can:presupuestos.editar', only: [
                'update', 'edit', 'agregarPartidaApu', 'agregarPartidaManual', 'actualizarPartida', 'eliminarPartida',
                'agregarGasto', 'actualizarGasto', 'eliminarGasto', 'aprobar', 'rechazar', 'archivar',
            ]),
        ];
    }

    public function index(): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $obras = Obra::orderBy('nombre')->get();
        $responsables = User::where('is_active', true)->orderBy('name')->get();

        return view('presupuestos.index', compact('clientes', 'obras', 'responsables'));
    }

    /**
     * Vista del "constructor" de presupuesto: aquí se agregan partidas
     * (desde el catálogo de APU o manuales) y gastos, con recálculo
     * en vivo de todos los totales sin recargar la página.
     */
    public function edit(Presupuesto $presupuesto): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $obras = Obra::orderBy('nombre')->get();
        $responsables = User::where('is_active', true)->orderBy('name')->get();
        $apus = \App\Models\Apu::where('estado', true)->orderBy('descripcion')->get();

        return view('presupuestos.edit', compact('presupuesto', 'clientes', 'obras', 'responsables', 'apus'));
    }

    public function datos(Request $request): JsonResponse
    {
        $presupuestos = Presupuesto::with(['cliente', 'obra', 'responsable'])
            ->when($request->get('estado'), function ($q, $estado) {
                return $q->where('estado', $estado);
            })
            ->when($request->get('cliente_id'), function ($q, $clienteId) {
                return $q->where('cliente_id', $clienteId);
            })
            ->when($request->get('obra_id'), function ($q, $obraId) {
                return $q->where('obra_id', $obraId);
            })
            ->latest()
            ->get()
            ->map(fn ($p) => array_merge($p->only(['id', 'codigo', 'fecha', 'estado', 'version', 'moneda']), [
                'cliente' => $p->cliente, 
                'obra' => $p->obra, 
                'responsable' => $p->responsable,
                'total_general' => round($p->total_general, 2),
            ]));

        return response()->json(['data' => $presupuestos]);
    }

    /**
     * Mostrar un presupuesto específico (para editar o ver detalles)
     */
    public function show(Presupuesto $presupuesto): JsonResponse
    {
        $presupuesto->load(['cliente', 'obra', 'responsable', 'partidas.apu', 'gastos']);

        return response()->json([
            'data' => array_merge($presupuesto->toArray(), [
                'totales' => $presupuesto->resumenTotales(),
                'partidas' => $presupuesto->partidas->map(fn ($p) => array_merge($p->toArray(), ['subtotal' => round($p->subtotal, 2)])),
                'gastos' => $presupuesto->gastos->map(fn ($g) => array_merge($g->toArray(), ['subtotal' => round($g->subtotal, 2)])),
            ]),
        ]);
    }

    protected function reglasPresupuesto(): array
    {
        return [
            'codigo' => ['nullable', 'string', 'max:30'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'obra_id' => ['nullable', 'exists:obras,id'],
            'responsable_id' => ['nullable', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'validez_dias' => ['required', 'integer', 'min:1'],
            'moneda' => ['required', 'string', 'max:10'],
            'igv' => ['required', 'numeric', 'min:0', 'max:100'],
            'descuento_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglasPresupuesto());
        $presupuesto = $this->service->crear($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto creado correctamente.', 'data' => $presupuesto]);
    }

    public function update(Request $request, Presupuesto $presupuesto): JsonResponse
    {
        $validated = $request->validate($this->reglasPresupuesto());
        $presupuesto = $this->service->actualizar($presupuesto, $validated);

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto actualizado correctamente.', 'data' => $presupuesto]);
    }

    public function agregarPartidaApu(Request $request, Presupuesto $presupuesto): JsonResponse
    {
        $datos = $request->validate([
            'apu_id' => ['required', 'exists:apus,id'], 
            'metrado' => ['required', 'numeric', 'min:0']
        ]);
        
        $partida = $this->service->agregarPartidaDesdeApu($presupuesto, $datos['apu_id'], $datos['metrado']);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Partida agregada.', 
            'data' => $partida, 
            'totales' => $presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function agregarPartidaManual(Request $request, Presupuesto $presupuesto): JsonResponse
    {
        $datos = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'unidad' => ['required', 'string', 'max:20'],
            'metrado' => ['required', 'numeric', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);
        
        $partida = $this->service->agregarPartidaManual($presupuesto, $datos);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Partida agregada.', 
            'data' => $partida, 
            'totales' => $presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function actualizarPartida(Request $request, PresupuestoPartida $partida): JsonResponse
    {
        $datos = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'unidad' => ['required', 'string', 'max:20'],
            'metrado' => ['required', 'numeric', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);
        
        $partida = $this->service->actualizarPartida($partida, $datos);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Partida actualizada.', 
            'data' => $partida, 
            'totales' => $partida->presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function eliminarPartida(PresupuestoPartida $partida): JsonResponse
    {
        $presupuesto = $partida->presupuesto;
        $this->service->eliminarPartida($partida);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Partida eliminada.', 
            'totales' => $presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function agregarGasto(Request $request, Presupuesto $presupuesto): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:transporte,hospedaje,viaticos,seguro,herramienta,otro'],
            'concepto' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'numeric', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);
        
        $gasto = $this->service->agregarGasto($presupuesto, $datos);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Gasto agregado.', 
            'data' => $gasto, 
            'totales' => $presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function actualizarGasto(Request $request, PresupuestoGasto $gasto): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:transporte,hospedaje,viaticos,seguro,herramienta,otro'],
            'concepto' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'numeric', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);
        
        $gasto = $this->service->actualizarGasto($gasto, $datos);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Gasto actualizado.', 
            'data' => $gasto, 
            'totales' => $gasto->presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function eliminarGasto(PresupuestoGasto $gasto): JsonResponse
    {
        $presupuesto = $gasto->presupuesto;
        $this->service->eliminarGasto($gasto);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Gasto eliminado.', 
            'totales' => $presupuesto->fresh()->resumenTotales()
        ]);
    }

    public function duplicar(Presupuesto $presupuesto): JsonResponse
    {
        $copia = $this->service->duplicar($presupuesto);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Presupuesto duplicado como ' . $copia->codigo, 
            'data' => $copia
        ]);
    }

    public function nuevaVersion(Presupuesto $presupuesto): JsonResponse
    {
        $nueva = $this->service->nuevaVersion($presupuesto);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Nueva versión v' . $nueva->version . ' creada.', 
            'data' => $nueva
        ]);
    }

    public function aprobar(Presupuesto $presupuesto): JsonResponse
    {
        $this->service->aprobar($presupuesto);

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto aprobado.']);
    }

    public function rechazar(Presupuesto $presupuesto): JsonResponse
    {
        $this->service->rechazar($presupuesto);

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto rechazado.']);
    }

    public function archivar(Presupuesto $presupuesto): JsonResponse
    {
        $this->service->archivar($presupuesto);

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto archivado.']);
    }

    public function restaurar(int $id): JsonResponse
    {
        $presupuesto = Presupuesto::withTrashed()->findOrFail($id);
        $presupuesto->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Presupuesto restaurado correctamente.']);
    }

    public function exportarPdf(Presupuesto $presupuesto)
    {
        $presupuesto->load(['cliente', 'obra', 'responsable', 'partidas', 'gastos']);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('presupuestos.pdf', [
            'presupuesto' => $presupuesto,
            'empresa' => $empresa,
            'totales' => $presupuesto->resumenTotales(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($presupuesto->codigo . '_' . now()->format('Ymd_His') . '.pdf');
    }
}