<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Obra;
use App\Models\Presupuesto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Centraliza los reportes gerenciales del sistema. Cada método
 * arma el mismo conjunto de datos que se muestra en pantalla y que
 * se exporta a PDF/Excel, para que el reporte impreso coincida
 * siempre con lo que el usuario ve.
 */
class ReporteController extends Controller implements HasMiddleware
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
            new Middleware('can:reportes.ver'),
        ];
    }

    public function index(): View
    {
        return view('reportes.index');
    }

    public function porCliente(Request $request)
    {
        $datos = Presupuesto::with('cliente')
            ->selectRaw('cliente_id, COUNT(*) as cantidad_presupuestos')
            ->groupBy('cliente_id')
            ->get()
            ->map(fn ($fila) => [
                'cliente' => $fila->cliente?->nombre ?? 'Sin cliente',
                'cantidad_presupuestos' => $fila->cantidad_presupuestos,
                'total' => Presupuesto::where('cliente_id', $fila->cliente_id)->get()->sum('total_general'),
            ]);

        return $this->responder($request, 'reportes.parciales.por-cliente', ['datos' => $datos], 'reporte_por_cliente');
    }

    public function porProyecto(Request $request)
    {
        $datos = Obra::withCount('presupuestos')->get()->map(fn ($obra) => [
            'obra' => $obra->nombre,
            'estado' => $obra->estado,
            'presupuestos' => $obra->presupuestos_count,
            'cliente' => $obra->cliente?->nombre ?? '—',
        ]);

        return $this->responder($request, 'reportes.parciales.por-proyecto', ['datos' => $datos], 'reporte_por_proyecto');
    }

    public function porMaterial(Request $request)
    {
        $datos = Material::with('categoria')->get()->map(fn ($m) => [
            'codigo' => $m->codigo, 
            'nombre' => $m->nombre, 
            'categoria' => $m->categoria?->nombre ?? '—',
            'stock' => $m->stock, 
            'precio_venta' => $m->precio_venta,
        ]);

        return $this->responder($request, 'reportes.parciales.por-material', ['datos' => $datos], 'reporte_por_material');
    }

    public function utilidadPorMes(Request $request)
    {
        $anio = $request->get('anio', now()->year);
        
        $datos = Presupuesto::whereYear('fecha', $anio)->where('estado', 'aprobado')->get()
            ->groupBy(fn ($p) => $p->fecha->format('m'))
            ->map(fn ($grupo, $mes) => [
                'mes' => $mes,
                'nombre_mes' => $this->getNombreMes($mes),
                'cantidad' => $grupo->count(),
                'total' => $grupo->sum('total_general'),
            ])->values();

        return $this->responder($request, 'reportes.parciales.utilidad-mensual', ['datos' => $datos, 'anio' => $anio], 'reporte_utilidad_mensual');
    }

    public function porEstadoPresupuesto(Request $request)
    {
        $datos = Presupuesto::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->get()
            ->map(fn ($item) => [
                'estado' => $item->estado,
                'cantidad' => $item->cantidad,
            ]);

        return $this->responder($request, 'reportes.parciales.por-estado', ['datos' => $datos], 'reporte_por_estado');
    }

    /**
     * Obtener datos para reportes en formato JSON (para vistas)
     */
    public function datosReporte(Request $request): JsonResponse
    {
        $tipo = $request->get('tipo', 'cliente');
        $anio = $request->get('anio', now()->year);

        switch ($tipo) {
            case 'cliente':
                $datos = Presupuesto::with('cliente')
                    ->selectRaw('cliente_id, COUNT(*) as cantidad_presupuestos')
                    ->groupBy('cliente_id')
                    ->get()
                    ->map(fn ($fila) => [
                        'cliente' => $fila->cliente?->nombre ?? 'Sin cliente',
                        'cantidad' => $fila->cantidad_presupuestos,
                        'total' => Presupuesto::where('cliente_id', $fila->cliente_id)->get()->sum('total_general'),
                    ]);
                break;

            case 'proyecto':
                $datos = Obra::withCount('presupuestos')->get()->map(fn ($obra) => [
                    'obra' => $obra->nombre,
                    'estado' => $obra->estado,
                    'presupuestos' => $obra->presupuestos_count,
                    'cliente' => $obra->cliente?->nombre ?? '—',
                ]);
                break;

            case 'material':
                $datos = Material::with('categoria')->get()->map(fn ($m) => [
                    'codigo' => $m->codigo,
                    'nombre' => $m->nombre,
                    'categoria' => $m->categoria?->nombre ?? '—',
                    'stock' => $m->stock,
                    'precio_venta' => $m->precio_venta,
                ]);
                break;

            case 'utilidad':
                $datos = Presupuesto::whereYear('fecha', $anio)->where('estado', 'aprobado')->get()
                    ->groupBy(fn ($p) => $p->fecha->format('m'))
                    ->map(fn ($grupo, $mes) => [
                        'mes' => $this->getNombreMes($mes),
                        'cantidad' => $grupo->count(),
                        'total' => $grupo->sum('total_general'),
                    ])->values();
                break;

            case 'estado':
                $datos = Presupuesto::selectRaw('estado, COUNT(*) as cantidad')
                    ->groupBy('estado')
                    ->get()
                    ->map(fn ($item) => [
                        'estado' => $item->estado,
                        'cantidad' => $item->cantidad,
                    ]);
                break;

            default:
                $datos = [];
        }

        return response()->json(['data' => $datos]);
    }

    /**
     * Obtener el nombre del mes en español
     */
    protected function getNombreMes(string $mes): string
    {
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        return $meses[$mes] ?? $mes;
    }

    /**
     * Decide si el reporte se muestra en pantalla (fragmento HTML vía
     * fetch), se exporta a PDF o se exporta a Excel, según el parámetro
     * "formato" de la petición. Mantiene una única fuente de datos
     * para las tres salidas.
     */
    protected function responder(Request $request, string $vista, array $datos, string $nombreArchivo)
    {
        $formato = $request->get('formato', 'html');

        if ($formato === 'pdf') {
            return Pdf::loadView($vista, $datos)
                ->setPaper('a4', 'landscape')
                ->download($nombreArchivo . '_' . now()->format('Ymd_His') . '.pdf');
        }

        if ($formato === 'excel') {
            $filas = collect($datos['datos']);

            return Excel::download(
                new class($filas) implements \Maatwebsite\Excel\Concerns\FromCollection {
                    public function __construct(protected $filas)
                    {
                    }

                    public function collection()
                    {
                        return $this->filas->map(fn ($f) => is_array($f) ? (object) $f : $f);
                    }
                },
                $nombreArchivo . '_' . now()->format('Ymd_His') . '.xlsx'
            );
        }

        return view($vista, $datos);
    }
}