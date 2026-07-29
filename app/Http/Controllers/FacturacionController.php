<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DocumentoVenta;
use App\Models\Empresa;
use App\Models\Presupuesto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FacturacionController extends Controller implements HasMiddleware
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
            new Middleware('can:facturacion.ver', only: ['index', 'datos']),
            new Middleware('can:facturacion.crear', only: ['store']),
            new Middleware('can:facturacion.editar', only: ['anular']),
        ];
    }

    public function index(): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $presupuestos = Presupuesto::orderBy('codigo')->get();

        return view('facturacion.index', compact('clientes', 'presupuestos'));
    }

    public function datos(): JsonResponse
    {
        $documentos = DocumentoVenta::with(['cliente', 'presupuesto'])->latest()->get();

        return response()->json(['data' => $documentos]);
    }

    /**
     * Mostrar un documento específico (para editar o ver detalles)
     */
    public function show(DocumentoVenta $documento_venta): JsonResponse
    {
        $documento_venta->load(['cliente', 'presupuesto', 'usuario']);
        return response()->json(['data' => $documento_venta]);
    }

    protected function siguienteNumero(string $tipo, ?string $serie): string
    {
        $ultimo = DocumentoVenta::where('tipo', $tipo)->where('serie', $serie)->withTrashed()->orderByDesc('id')->first();

        return str_pad((string) (($ultimo ? (int) $ultimo->numero : 0) + 1), 6, '0', STR_PAD_LEFT);
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:cotizacion,proforma,factura,boleta,orden_servicio'],
            'serie' => ['nullable', 'string', 'max:10'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'presupuesto_id' => ['nullable', 'exists:presupuestos,id'],
            'fecha' => ['required', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'igv' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $datos['numero'] = $this->siguienteNumero($datos['tipo'], $datos['serie'] ?? null);
        $datos['usuario_id'] = $request->user()->id;

        $documento = DocumentoVenta::create($datos);

        return response()->json(['ok' => true, 'mensaje' => 'Documento generado correctamente.', 'data' => $documento]);
    }

    public function anular(DocumentoVenta $documento_venta): JsonResponse
    {
        $documento_venta->update(['estado' => 'anulado']);

        return response()->json(['ok' => true, 'mensaje' => 'Documento anulado correctamente.']);
    }

    public function restaurar(int $id): JsonResponse
    {
        $documento = DocumentoVenta::withTrashed()->findOrFail($id);
        $documento->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Documento restaurado correctamente.']);
    }

    public function exportarPdf(DocumentoVenta $documento_venta)
    {
        $documento_venta->load(['cliente', 'presupuesto']);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('facturacion.pdf', ['documento' => $documento_venta, 'empresa' => $empresa]);

        return $pdf->download($documento_venta->tipo.'_'.$documento_venta->numero_completo.'.pdf');
    }

    public function destroy(DocumentoVenta $documento_venta): JsonResponse
    {
        $documento_venta->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Documento eliminado correctamente.']);
    }
}