<?php

namespace App\Http\Controllers;

use App\Models\Apu;
use App\Models\Equipo;
use App\Models\ManoObra;
use App\Models\Maquinaria;
use App\Models\Material;
use App\Services\ApuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApuController extends Controller implements HasMiddleware
{
    public function __construct(protected ApuService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:apu.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:apu.crear', only: ['store']),
            new Middleware('can:apu.editar', only: ['update']),
            new Middleware('can:apu.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $materiales = Material::where('estado', true)->orderBy('nombre')->get();
        $manoObra = ManoObra::where('estado', true)->orderBy('nombre')->get();
        $equipos = Equipo::where('estado', true)->orderBy('nombre')->get();
        $maquinarias = Maquinaria::where('estado', true)->orderBy('nombre')->get();

        return view('apu.index', compact('materiales', 'manoObra', 'equipos', 'maquinarias'));
    }

    public function datos(): JsonResponse
    {
        $apus = Apu::withCount('materiales')->latest()->get()
            ->map(fn ($apu) => array_merge($apu->only(['id', 'codigo', 'descripcion', 'unidad', 'estado']), $apu->resumenCostos()));

        return response()->json(['data' => $apus]);
    }

    public function show(Apu $apu): JsonResponse
    {
        $apu->load(['materiales.material', 'manoObra.manoObra', 'equipos.equipo', 'maquinarias.maquinaria']);

        return response()->json([
            'data' => array_merge($apu->toArray(), ['resumen' => $apu->resumenCostos()]),
        ]);
    }

    protected function reglas(): array
    {
        return [
            'codigo' => ['nullable', 'string', 'max:30'],
            'descripcion' => ['required', 'string', 'max:255'],
            'unidad' => ['required', 'string', 'max:20'],
            'rendimiento' => ['required', 'numeric', 'min:0.0001'],
            'porcentaje_herramientas' => ['required', 'numeric', 'min:0', 'max:100'],
            'porcentaje_costos_indirectos' => ['required', 'numeric', 'min:0', 'max:100'],
            'porcentaje_utilidad' => ['required', 'numeric', 'min:0', 'max:100'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
            'materiales' => ['array'],
            'materiales.*.material_id' => ['required', 'exists:materiales,id'],
            'materiales.*.cantidad' => ['required', 'numeric', 'min:0'],
            'materiales.*.desperdicio_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'mano_obra' => ['array'],
            'mano_obra.*.mano_obra_id' => ['required', 'exists:mano_obras,id'],
            'mano_obra.*.cantidad' => ['required', 'numeric', 'min:0'],
            'equipos' => ['array'],
            'equipos.*.equipo_id' => ['required', 'exists:equipos,id'],
            'equipos.*.cantidad' => ['required', 'numeric', 'min:0'],
            'maquinarias' => ['array'],
            'maquinarias.*.maquinaria_id' => ['required', 'exists:maquinarias,id'],
            'maquinarias.*.cantidad' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $apu = $this->service->crear($validated);

        return response()->json(['ok' => true, 'mensaje' => 'APU creado correctamente.', 'data' => $apu]);
    }

    public function update(Request $request, Apu $apu): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $apu = $this->service->actualizar($apu, $validated);

        return response()->json(['ok' => true, 'mensaje' => 'APU actualizado correctamente.', 'data' => $apu]);
    }

    public function destroy(Apu $apu): JsonResponse
    {
        $apu->delete();

        return response()->json(['ok' => true, 'mensaje' => 'APU eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $apu = Apu::withTrashed()->findOrFail($id);
        $apu->restore();

        return response()->json(['ok' => true, 'mensaje' => 'APU restaurado correctamente.']);
    }
}