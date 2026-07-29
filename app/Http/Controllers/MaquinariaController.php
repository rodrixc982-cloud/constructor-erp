<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MaquinariaController extends Controller implements HasMiddleware
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
            new Middleware('can:maquinaria.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:maquinaria.crear', only: ['store']),
            new Middleware('can:maquinaria.editar', only: ['update']),
            new Middleware('can:maquinaria.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('maquinaria.index');
    }

    /**
     * Obtener una maquinaria específica (para editar)
     */
    public function show(Maquinaria $maquinaria): JsonResponse
    {
        return response()->json(['data' => $maquinaria]);
    }

    public function datos(): JsonResponse
    {
        return response()->json(['data' => Maquinaria::latest()->get()]);
    }

    protected function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:20'],
            'costo_hora' => ['required', 'numeric', 'min:0'],
            'costo_dia' => ['required', 'numeric', 'min:0'],
            'costo_mensual' => ['required', 'numeric', 'min:0'],
            'disponible' => ['boolean'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $maquinaria = Maquinaria::create($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Maquinaria registrada correctamente.', 'data' => $maquinaria]);
    }

    public function update(Request $request, Maquinaria $maquinaria): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $maquinaria->update($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Maquinaria actualizada correctamente.', 'data' => $maquinaria]);
    }

    public function destroy(Maquinaria $maquinaria): JsonResponse
    {
        $maquinaria->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Maquinaria eliminada correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $maquinaria = Maquinaria::withTrashed()->findOrFail($id);
        $maquinaria->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Maquinaria restaurada correctamente.']);
    }
}