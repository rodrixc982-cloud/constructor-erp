<?php

namespace App\Http\Controllers;

use App\Models\EventoCalendario;
use App\Models\Obra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CalendarioController extends Controller implements HasMiddleware
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
            new Middleware('can:calendario.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:calendario.crear', only: ['store']),
            new Middleware('can:calendario.editar', only: ['update']),
            new Middleware('can:calendario.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $obras = Obra::orderBy('nombre')->get();

        return view('calendario.index', compact('obras'));
    }

    /**
     * Mostrar un evento específico (para editar)
     */
    public function show(EventoCalendario $evento): JsonResponse
    {
        $evento->load(['obra', 'usuario']);
        return response()->json(['data' => $evento]);
    }

    public function datos(Request $request): JsonResponse
    {
        $eventos = EventoCalendario::with(['obra', 'usuario'])
            ->when($request->get('mes'), function ($q, $mes) {
                return $q->whereMonth('fecha_inicio', $mes);
            })
            ->when($request->get('anio'), function ($q, $anio) {
                return $q->whereYear('fecha_inicio', $anio);
            })
            ->when($request->get('fecha_inicio'), function ($q, $fecha) {
                return $q->whereDate('fecha_inicio', $fecha);
            })
            ->orderBy('fecha_inicio')
            ->get();

        return response()->json(['data' => $eventos]);
    }

    protected function reglas(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:agenda,recordatorio,visita,reunion,inspeccion'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'obra_id' => ['nullable', 'exists:obras,id'],
            'descripcion' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $validated['usuario_id'] = $request->user()->id;
        
        $evento = EventoCalendario::create($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Evento registrado correctamente.', 'data' => $evento]);
    }

    public function update(Request $request, EventoCalendario $evento): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $evento->update($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Evento actualizado correctamente.', 'data' => $evento]);
    }

    public function destroy(EventoCalendario $evento): JsonResponse
    {
        $evento->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Evento eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $evento = EventoCalendario::withTrashed()->findOrFail($id);
        $evento->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Evento restaurado correctamente.']);
    }
}