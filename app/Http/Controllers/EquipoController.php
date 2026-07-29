<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EquipoController extends Controller implements HasMiddleware
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
            new Middleware('can:equipos.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:equipos.crear', only: ['store']),
            new Middleware('can:equipos.editar', only: ['update']),
            new Middleware('can:equipos.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('equipos.index');
    }

    /**
     * Obtener un equipo específico (para editar)
     */
    public function show(Equipo $equipo): JsonResponse
    {
        return response()->json(['data' => $equipo]);
    }

    public function datos(): JsonResponse
    {
        return response()->json(['data' => Equipo::latest()->get()]);
    }

    protected function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:255'],
            'costo_alquiler_dia' => ['required', 'numeric', 'min:0'],
            'costo_mantenimiento' => ['required', 'numeric', 'min:0'],
            'disponible' => ['boolean'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $equipo = Equipo::create($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Equipo registrado correctamente.', 'data' => $equipo]);
    }

    public function update(Request $request, Equipo $equipo): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $equipo->update($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Equipo actualizado correctamente.', 'data' => $equipo]);
    }

    public function destroy(Equipo $equipo): JsonResponse
    {
        $equipo->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Equipo eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $equipo = Equipo::withTrashed()->findOrFail($id);
        $equipo->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Equipo restaurado correctamente.']);
    }
}