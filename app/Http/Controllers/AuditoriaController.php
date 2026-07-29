<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaController extends Controller implements HasMiddleware
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
            new Middleware('can:auditoria.ver'),
        ];
    }

    public function index(): View
    {
        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('auditoria.index', compact('logNames'));
    }

    public function datos(Request $request): JsonResponse
    {
        $actividades = Activity::with('causer')
            ->when($request->get('log_name'), function ($q, $logName) {
                return $q->where('log_name', $logName);
            })
            ->when($request->get('desde'), function ($q, $desde) {
                return $q->whereDate('created_at', '>=', $desde);
            })
            ->when($request->get('hasta'), function ($q, $hasta) {
                return $q->whereDate('created_at', '<=', $hasta);
            })
            ->when($request->get('usuario_id'), function ($q, $usuarioId) {
                return $q->where('causer_id', $usuarioId);
            })
            ->when($request->get('buscar'), function ($q, $buscar) {
                return $q->where('description', 'LIKE', "%{$buscar}%")
                    ->orWhere('log_name', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->limit(500)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'fecha' => $a->created_at->format('d/m/Y H:i:s'),
                'usuario' => $a->causer?->name ?? 'Sistema',
                'usuario_id' => $a->causer?->id,
                'log_name' => $a->log_name ?? 'Sin categoría',
                'descripcion' => $a->description,
                'valores_anteriores' => $a->properties['old'] ?? null,
                'valores_nuevos' => $a->properties['attributes'] ?? null,
                'ip' => $a->ip_address ?? '—',
                'user_agent' => $a->user_agent ?? '—',
            ]);

        return response()->json(['data' => $actividades]);
    }

    /**
     * Obtener estadísticas de auditoría
     */
    public function estadisticas(): JsonResponse
    {
        $totalActividades = Activity::count();
        
        $actividadesPorDia = Activity::selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->limit(30)
            ->get();

        $actividadesPorTipo = Activity::select('log_name')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('log_name')
            ->get();

        $actividadesPorUsuario = Activity::with('causer')
            ->select('causer_id')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('causer_id')
            ->groupBy('causer_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'usuario' => $item->causer?->name ?? 'Sistema',
                'total' => $item->total,
            ]);

        return response()->json([
            'total' => $totalActividades,
            'por_dia' => $actividadesPorDia,
            'por_tipo' => $actividadesPorTipo,
            'por_usuario' => $actividadesPorUsuario,
        ]);
    }

    /**
     * Obtener detalles de una actividad específica
     */
    public function show(int $id): JsonResponse
    {
        $actividad = Activity::with('causer')->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $actividad->id,
                'fecha' => $actividad->created_at->format('d/m/Y H:i:s'),
                'usuario' => $actividad->causer?->name ?? 'Sistema',
                'log_name' => $actividad->log_name,
                'descripcion' => $actividad->description,
                'valores_anteriores' => $actividad->properties['old'] ?? null,
                'valores_nuevos' => $actividad->properties['attributes'] ?? null,
                'ip' => $actividad->ip_address ?? '—',
                'user_agent' => $actividad->user_agent ?? '—',
                'url' => $actividad->properties['url'] ?? null,
                'method' => $actividad->properties['method'] ?? null,
            ]
        ]);
    }

    /**
     * Limpiar registros de auditoría antiguos
     */
    public function limpiar(Request $request): JsonResponse
    {
        $dias = $request->get('dias', 90);
        
        $fechaLimite = now()->subDays($dias);
        $eliminados = Activity::where('created_at', '<', $fechaLimite)->delete();

        return response()->json([
            'ok' => true, 
            'mensaje' => "Se eliminaron {$eliminados} registros de auditoría anteriores a {$dias} días.",
            'eliminados' => $eliminados
        ]);
    }
}