<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use App\Services\ProveedorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProveedorController extends Controller implements HasMiddleware
{
    public function __construct(protected ProveedorService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:proveedores.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:proveedores.crear', only: ['store']),
            new Middleware('can:proveedores.editar', only: ['update']),
            new Middleware('can:proveedores.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('proveedores.index');
    }

    /**
     * Obtener un proveedor específico (para editar)
     */
    public function show(Proveedor $proveedor): JsonResponse
    {
        return response()->json(['data' => $proveedor]);
    }

    public function datos(Request $request): JsonResponse
    {
        $proveedores = $this->service->listar(['buscar' => $request->get('buscar')], 100000);

        return response()->json(['data' => $proveedores->items()]);
    }

    public function store(ProveedorRequest $request): JsonResponse
    {
        $proveedor = $this->service->crear($request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Proveedor registrado correctamente.', 'data' => $proveedor]);
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor): JsonResponse
    {
        $proveedor = $this->service->actualizar($proveedor->id, $request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Proveedor actualizado correctamente.', 'data' => $proveedor]);
    }

    public function destroy(Proveedor $proveedor): JsonResponse
    {
        $this->service->eliminar($proveedor->id);

        return response()->json(['ok' => true, 'mensaje' => 'Proveedor eliminado correctamente.']);
    }

    public function restore(int $id): RedirectResponse
    {
        $this->service->restaurar($id);

        return back()->with('status', 'Proveedor restaurado correctamente.');
    }
}