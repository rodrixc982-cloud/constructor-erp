<?php

namespace App\Http\Controllers;

use App\Models\ManoObra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ManoObraController extends Controller implements HasMiddleware
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
            new Middleware('can:mano_obra.ver', only: ['index', 'datos']),
            new Middleware('can:mano_obra.crear', only: ['store']),
            new Middleware('can:mano_obra.editar', only: ['update']),
            new Middleware('can:mano_obra.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('mano-obra.index');
    }

    public function datos(): JsonResponse
    {
        return response()->json(['data' => ManoObra::latest()->get()]);
    }

    /**
     * Obtener una mano de obra específica (para editar)
     */
    public function show(ManoObra $mano_obra): JsonResponse
    {
        return response()->json(['data' => $mano_obra]);
    }

    protected function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'especialidad' => ['required', 'in:Albañil,Ayudante,Maestro,Pintor,Electricista,Gasfitero,Carpintero,Soldador,Ingeniero,Arquitecto'],
            'documento' => ['nullable', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'tipo_costo' => ['required', 'in:hora,dia,semana,mes,m2,ml,m3'],
            'costo' => ['required', 'numeric', 'min:0'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $mano = ManoObra::create($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Registrado correctamente.', 'data' => $mano]);
    }

    public function update(Request $request, ManoObra $mano_obra): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $mano_obra->update($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Actualizado correctamente.', 'data' => $mano_obra]);
    }

    public function destroy(ManoObra $mano_obra): JsonResponse
    {
        $mano_obra->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $mano = ManoObra::withTrashed()->findOrFail($id);
        $mano->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Restaurado correctamente.']);
    }
}