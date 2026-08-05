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

    /**
     * Vista principal de facturación
     */
    public function index(): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $presupuestos = Presupuesto::orderBy('codigo')->get();

        return view('facturacion.index', compact('clientes', 'presupuestos'));
    }

    /**
     * Datos para DataTable (CORREGIDO)
     */
    public function datos(): JsonResponse
    {
        $documentos = DocumentoVenta::with(['cliente', 'presupuesto'])->latest()->get();
        
        // Transformar los datos para incluir 'numero_completo'
        $data = $documentos->map(function($doc) {
            // Generar número completo
            $numeroCompleto = $doc->serie 
                ? $doc->serie . '-' . str_pad($doc->numero, 8, '0', STR_PAD_LEFT)
                : str_pad($doc->numero, 8, '0', STR_PAD_LEFT);
            
            return [
                'id' => $doc->id,
                'tipo' => $doc->tipo,
                'numero_completo' => $numeroCompleto,
                'serie' => $doc->serie,
                'numero' => $doc->numero,
                'cliente' => $doc->cliente,
                'fecha' => $doc->fecha ? $doc->fecha->format('Y-m-d') : null,
                'subtotal' => (float) $doc->subtotal,
                'igv' => (float) $doc->igv,
                'total' => (float) $doc->total,
                'estado' => $doc->estado,
                'observaciones' => $doc->observaciones,
                'presupuesto' => $doc->presupuesto,
                'usuario_id' => $doc->usuario_id,
                'created_at' => $doc->created_at,
                'updated_at' => $doc->updated_at,
                'deleted_at' => $doc->deleted_at,
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Mostrar un documento específico (para editar o ver detalles)
     */
    public function show(DocumentoVenta $documento_venta): JsonResponse
    {
        $documento_venta->load(['cliente', 'presupuesto', 'usuario']);
        return response()->json(['data' => $documento_venta]);
    }

    /**
     * Generar el siguiente número de documento
     */
    protected function siguienteNumero(string $tipo, ?string $serie): string
    {
        $ultimo = DocumentoVenta::where('tipo', $tipo)
            ->where('serie', $serie)
            ->withTrashed()
            ->orderByDesc('id')
            ->first();

        return str_pad((string) (($ultimo ? (int) $ultimo->numero : 0) + 1), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Crear un nuevo documento
     */
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
        $datos['estado'] = 'emitido';

        $documento = DocumentoVenta::create($datos);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Documento generado correctamente.', 
            'data' => $documento
        ]);
    }

    /**
     * Anular un documento
     */
    public function anular(DocumentoVenta $documento_venta): JsonResponse
    {
        if ($documento_venta->estado === 'anulado') {
            return response()->json([
                'ok' => false, 
                'mensaje' => 'El documento ya está anulado.'
            ], 400);
        }

        $documento_venta->update(['estado' => 'anulado']);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Documento anulado correctamente.'
        ]);
    }

    /**
     * Restaurar un documento eliminado
     */
    public function restaurar(int $id): JsonResponse
    {
        $documento = DocumentoVenta::withTrashed()->findOrFail($id);
        $documento->restore();

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Documento restaurado correctamente.'
        ]);
    }

    /**
     * Descargar PDF (CORREGIDO)
     */
    public function exportarPdf(DocumentoVenta $documento_venta)
    {
        $documento_venta->load(['cliente', 'presupuesto']);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('facturacion.pdf', [
            'documento' => $documento_venta, 
            'empresa' => $empresa
        ]);

        return $pdf->download($documento_venta->tipo . '_' . $documento_venta->numero_completo . '.pdf');
    }

    /**
     * Ver PDF en el navegador (NUEVO MÉTODO)
     */
    public function verPdf(DocumentoVenta $documento_venta)
    {
        $documento_venta->load(['cliente', 'presupuesto']);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('facturacion.pdf', [
            'documento' => $documento_venta, 
            'empresa' => $empresa
        ]);

        return $pdf->stream($documento_venta->tipo . '_' . $documento_venta->numero_completo . '.pdf');
    }

    /**
     * Eliminar un documento (soft delete)
     */
    public function destroy(DocumentoVenta $documento_venta): JsonResponse
    {
        $documento_venta->delete();

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Documento eliminado correctamente.'
        ]);
    }

    /**
     * Obtener documentos eliminados (para restaurar)
     */
    public function eliminados(): JsonResponse
    {
        $documentos = DocumentoVenta::onlyTrashed()
            ->with(['cliente', 'presupuesto'])
            ->latest()
            ->get();

        return response()->json(['data' => $documentos]);
    }

    /**
     * Estadísticas de facturación
     */
    public function estadisticas(): JsonResponse
    {
        $totalDocumentos = DocumentoVenta::count();
        $totalAnulados = DocumentoVenta::where('estado', 'anulado')->count();
        $totalEmitidos = DocumentoVenta::where('estado', 'emitido')->count();
        $totalPagados = DocumentoVenta::where('estado', 'pagado')->count();
        
        $totalMonto = DocumentoVenta::where('estado', '!=', 'anulado')->sum('total');

        return response()->json([
            'total_documentos' => $totalDocumentos,
            'total_anulados' => $totalAnulados,
            'total_emitidos' => $totalEmitidos,
            'total_pagados' => $totalPagados,
            'total_monto' => (float) $totalMonto,
        ]);
    }

    /**
     * Cambiar estado de un documento
     */
    public function cambiarEstado(Request $request, DocumentoVenta $documento_venta): JsonResponse
    {
        $request->validate([
            'estado' => ['required', 'in:emitido,pagado,anulado,cancelado']
        ]);

        $documento_venta->update(['estado' => $request->estado]);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Estado actualizado correctamente.',
            'data' => $documento_venta
        ]);
    }

    /**
     * Duplicar un documento (para crear uno nuevo basado en otro)
     */
    public function duplicar(DocumentoVenta $documento_venta): JsonResponse
    {
        $nuevoDocumento = $documento_venta->replicate();
        $nuevoDocumento->numero = $this->siguienteNumero(
            $documento_venta->tipo, 
            $documento_venta->serie
        );
        $nuevoDocumento->estado = 'emitido';
        $nuevoDocumento->fecha = now();
        $nuevoDocumento->save();

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Documento duplicado correctamente.',
            'data' => $nuevoDocumento
        ]);
    }
}