<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoriaController extends Controller implements HasMiddleware
{
    public function __construct(protected CategoriaService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:categorias.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:categorias.crear', only: ['store']),
            new Middleware('can:categorias.editar', only: ['update']),
            new Middleware('can:categorias.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('categorias.index');
    }

    /**
     * Obtener una categoría específica (para editar)
     */
    public function show(Categoria $categoria): JsonResponse
    {
        return response()->json(['data' => $categoria]);
    }

    public function datos(Request $request): JsonResponse
    {
        $categorias = $this->service->listar(['buscar' => $request->get('buscar')], 100000);

        return response()->json(['data' => $categorias->items()]);
    }

    public function store(CategoriaRequest $request): JsonResponse
    {
        $categoria = $this->service->crear($request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Categoría creada correctamente.', 'data' => $categoria]);
    }

    public function update(CategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        $categoria = $this->service->actualizar($categoria->id, $request->validated());

        return response()->json(['ok' => true, 'mensaje' => 'Categoría actualizada correctamente.', 'data' => $categoria]);
    }

    public function destroy(Categoria $categoria): JsonResponse
    {
        $this->service->eliminar($categoria->id);

        return response()->json(['ok' => true, 'mensaje' => 'Categoría eliminada correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $this->service->restaurar($id);

        return response()->json(['ok' => true, 'mensaje' => 'Categoría restaurada correctamente.']);
    }
}