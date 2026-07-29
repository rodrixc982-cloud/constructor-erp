<?php

namespace App\Http\Controllers;

use App\Exports\MaterialesExport;
use App\Imports\MaterialesImport;
use App\Http\Requests\MaterialRequest;
use App\Models\Categoria;
use App\Models\Material;
use App\Models\Proveedor;
use App\Services\MaterialService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MaterialController extends Controller implements HasMiddleware
{
    public function __construct(protected MaterialService $service)
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:materiales.ver', only: ['index', 'datos', 'qr', 'show']),
            new Middleware('can:materiales.crear', only: ['store', 'importar']),
            new Middleware('can:materiales.editar', only: ['update']),
            new Middleware('can:materiales.eliminar', only: ['destroy']),
            new Middleware('can:materiales.exportar', only: ['exportarExcel', 'exportarPdf']),
        ];
    }

    public function index(): View
    {
        $categorias = Categoria::where('estado', true)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('estado', true)->orderBy('nombre')->get();

        return view('materiales.index', compact('categorias', 'proveedores'));
    }

    /**
     * Mostrar un material específico (para editar)
     */
    public function show(Material $material): JsonResponse
    {
        return response()->json(['data' => $material]);
    }

    public function datos(Request $request): JsonResponse
    {
        $filtros = [
            'buscar' => $request->get('buscar'),
            'categoria_id' => $request->get('categoria_id'),
            'stock_bajo' => $request->boolean('stock_bajo'),
        ];

        $materiales = $this->service->listar($filtros, 100000);

        return response()->json(['data' => $materiales->items()]);
    }

    public function store(MaterialRequest $request): JsonResponse
    {
        $material = $this->service->crear($request->validated(), $request->file('imagen'));

        return response()->json(['ok' => true, 'mensaje' => 'Material registrado correctamente.', 'data' => $material]);
    }

    public function update(MaterialRequest $request, Material $material): JsonResponse
    {
        $material = $this->service->actualizar($material->id, $request->validated(), $request->file('imagen'));

        return response()->json(['ok' => true, 'mensaje' => 'Material actualizado correctamente.', 'data' => $material]);
    }

    public function destroy(Material $material): JsonResponse
    {
        $this->service->eliminar($material->id);

        return response()->json(['ok' => true, 'mensaje' => 'Material eliminado correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $this->service->restaurar($id);

        return response()->json(['ok' => true, 'mensaje' => 'Material restaurado correctamente.']);
    }

    /**
     * Devuelve el QR del material en SVG para mostrar/imprimir su etiqueta.
     */
    public function qr(Material $material)
    {
        $svg = $this->service->generarQr($material);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function exportarExcel(Request $request)
    {
        $filtros = ['categoria_id' => $request->get('categoria_id')];

        return Excel::download(new MaterialesExport($filtros), 'materiales_'.now()->format('Ymd_His').'.xlsx');
    }

    public function exportarPdf(Request $request)
    {
        $materiales = Material::with(['categoria', 'proveedor'])
            ->when($request->get('categoria_id'), fn ($q) => $q->where('categoria_id', $request->get('categoria_id')))
            ->orderBy('nombre')
            ->get();

        $empresa = \App\Models\Empresa::first();

        $pdf = Pdf::loadView('materiales.pdf', compact('materiales', 'empresa'))->setPaper('a4', 'landscape');

        return $pdf->download('materiales_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Descarga la plantilla Excel de ejemplo para importar materiales.
     */
    public function plantillaImportacion()
    {
        $filas = collect([
            ['codigo' => '', 'nombre' => 'Cemento Portland Tipo I', 'marca' => 'Sol', 'modelo' => 'Bolsa 42.5kg', 'categoria' => 'Cemento', 'unidad' => 'BLS', 'precio_compra' => 24.5, 'precio_venta' => 28.0, 'stock' => 100, 'stock_minimo' => 20, 'iva' => 18, 'proveedor' => ''],
        ]);

        return Excel::download(new class($filas) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(protected $filas)
            {
            }

            public function collection()
            {
                return $this->filas;
            }

            public function headings(): array
            {
                return ['codigo', 'nombre', 'marca', 'modelo', 'categoria', 'unidad', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'iva', 'proveedor'];
            }
        }, 'plantilla_materiales.xlsx');
    }

    public function importar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'archivo' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'errores' => $validator->errors()], 422);
        }

        $import = new MaterialesImport;
        Excel::import($import, $request->file('archivo'));

        return response()->json(['ok' => true, 'mensaje' => 'Materiales importados correctamente.']);
    }
}