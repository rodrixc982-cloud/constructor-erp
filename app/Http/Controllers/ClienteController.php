<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClienteController extends Controller implements HasMiddleware
{
    public function __construct(protected ClienteService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:clientes.ver', only: ['index', 'datos']),
            new Middleware('can:clientes.crear', only: ['store']),
            new Middleware('can:clientes.editar', only: ['update']),
            new Middleware('can:clientes.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('clientes.index');
    }

    /**
     * Endpoint JSON consumido por DataTables (procesamiento client-side).
     */
    public function datos(Request $request): JsonResponse
    {
        $clientes = $this->service->listar(['buscar' => $request->get('buscar')], 100000);

        return response()->json(['data' => $clientes->items()]);
    }

    public function store(ClienteRequest $request): JsonResponse
    {
        $cliente = $this->service->crear($request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Cliente registrado correctamente.', 'data' => $cliente]);
    }

    public function update(ClienteRequest $request, Cliente $cliente): JsonResponse
    {
        $cliente = $this->service->actualizar($cliente->id, $request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Cliente actualizado correctamente.', 'data' => $cliente]);
    }

    public function destroy(Cliente $cliente): JsonResponse
    {
        $this->service->eliminar($cliente->id);

        return response()->json(['ok' => true, 'mensaje' => 'Cliente eliminado correctamente.']);
    }

    public function restore(int $id): RedirectResponse
    {
        $this->service->restaurar($id);

        return back()->with('status', 'Cliente restaurado correctamente.');
    }
}