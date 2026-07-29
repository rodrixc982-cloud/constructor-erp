<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Material;
use App\Models\MovimientoInventario;
use App\Services\InventarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InventarioController extends Controller implements HasMiddleware
{
    public function __construct(protected InventarioService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:inventario.ver', only: ['index', 'datos', 'kardex']),
            new Middleware('can:inventario.crear', only: ['entrada', 'salida', 'transferencia', 'ajuste', 'guardarAlmacen']),
        ];
    }

    public function index(): View
    {
        $almacenes = Almacen::where('estado', true)->orderBy('nombre')->get();
        $materiales = Material::where('estado', true)->orderBy('nombre')->get();
        $alertas = $this->service->materialesStockBajo();

        return view('inventario.index', compact('almacenes', 'materiales', 'alertas'));
    }

    public function datos(Request $request): JsonResponse
    {
        $movimientos = MovimientoInventario::with(['material', 'almacen', 'almacenDestino', 'usuario'])
            ->when($request->get('material_id'), fn ($q) => $q->where('material_id', $request->get('material_id')))
            ->when($request->get('almacen_id'), fn ($q) => $q->where('almacen_id', $request->get('almacen_id')))
            ->latest()
            ->limit(500)
            ->get();

        return response()->json(['data' => $movimientos]);
    }

    public function kardex(Material $material): JsonResponse
    {
        return response()->json(['data' => $this->service->kardex($material->id)]);
    }

    public function entrada(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'material_id' => ['required', 'exists:materiales,id'],
            'almacen_id' => ['required', 'exists:almacenes,id'],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $this->service->registrarEntrada(
            $datos['material_id'],
            $datos['almacen_id'],
            $datos['cantidad'],
            $datos['motivo'] ?? 'Entrada manual',
            auth()->id(),
            $datos['observaciones'] ?? null
        );

        return response()->json(['ok' => true, 'mensaje' => 'Entrada registrada correctamente.']);
    }

    public function salida(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'material_id' => ['required', 'exists:materiales,id'],
            'almacen_id' => ['required', 'exists:almacenes,id'],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $this->service->registrarSalida(
            $datos['material_id'],
            $datos['almacen_id'],
            $datos['cantidad'],
            $datos['motivo'] ?? 'Salida manual',
            auth()->id(),
            $datos['observaciones'] ?? null
        );

        return response()->json(['ok' => true, 'mensaje' => 'Salida registrada correctamente.']);
    }

    public function transferencia(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'material_id' => ['required', 'exists:materiales,id'],
            'almacen_origen_id' => ['required', 'exists:almacenes,id', 'different:almacen_destino_id'],
            'almacen_destino_id' => ['required', 'exists:almacenes,id'],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $this->service->transferir(
            $datos['material_id'],
            $datos['almacen_origen_id'],
            $datos['almacen_destino_id'],
            $datos['cantidad'],
            auth()->id(),
            $datos['observaciones'] ?? null
        );

        return response()->json(['ok' => true, 'mensaje' => 'Transferencia registrada correctamente.']);
    }

    public function ajuste(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'material_id' => ['required', 'exists:materiales,id'],
            'almacen_id' => ['required', 'exists:almacenes,id'],
            'nueva_cantidad' => ['required', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $this->service->ajustar(
            $datos['material_id'],
            $datos['almacen_id'],
            $datos['nueva_cantidad'],
            auth()->id(),
            $datos['observaciones'] ?? null
        );

        return response()->json(['ok' => true, 'mensaje' => 'Ajuste registrado correctamente.']);
    }

    public function guardarAlmacen(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'id' => ['nullable', 'exists:almacenes,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'responsable' => ['nullable', 'string', 'max:255'],
            'estado' => ['boolean'],
        ]);

        $almacen = Almacen::updateOrCreate(['id' => $datos['id'] ?? null], $datos);

        return response()->json(['ok' => true, 'mensaje' => 'Almacén guardado correctamente.', 'data' => $almacen]);
    }
}