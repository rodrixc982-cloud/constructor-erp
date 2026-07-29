<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Obra;
use App\Models\ObraAdjunto;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ObraController extends Controller implements HasMiddleware
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
            new Middleware('can:obras.ver', only: ['index', 'datos', 'show', 'ver']),
            new Middleware('can:obras.crear', only: ['store']),
            new Middleware('can:obras.editar', only: ['update']),
            new Middleware('can:obras.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $responsables = User::where('is_active', true)->orderBy('name')->get();

        return view('obras.index', compact('clientes', 'responsables'));
    }

    public function datos(): JsonResponse
    {
        $obras = Obra::with(['cliente', 'responsable'])->latest()->get();

        return response()->json(['data' => $obras]);
    }

    /**
     * Obtener datos de la obra en formato JSON (para editar)
     */
    public function show(Obra $obra): JsonResponse
    {
        return response()->json(['data' => $obra]);
    }

    /**
     * Mostrar la vista de detalles de la obra
     */
    public function ver(Obra $obra): View
    {
        $obra->load(['cliente', 'responsable', 'adjuntos']);

        return view('obras.show', compact('obra'));
    }

    protected function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'latitud' => ['nullable', 'numeric'],
            'longitud' => ['nullable', 'numeric'],
            'responsable_id' => ['nullable', 'exists:users,id'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin_estimada' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:planificacion,activa,pausada,terminada,cancelada'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $obra = Obra::create($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Obra registrada correctamente.', 'data' => $obra]);
    }

    public function update(Request $request, Obra $obra): JsonResponse
    {
        $validated = $request->validate($this->reglas());
        $obra->update($validated);

        return response()->json(['ok' => true, 'mensaje' => 'Obra actualizada correctamente.', 'data' => $obra]);
    }

    public function destroy(Obra $obra): JsonResponse
    {
        $obra->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Obra eliminada correctamente.']);
    }

    public function restore(int $id): JsonResponse
    {
        $obra = Obra::withTrashed()->findOrFail($id);
        $obra->restore();

        return response()->json(['ok' => true, 'mensaje' => 'Obra restaurada correctamente.']);
    }

    /**
     * Sube fotos, planos, documentos o contratos asociados a la obra.
     * Acepta los formatos definidos en el prompt: PDF, Excel, Word, DWG, JPG, PNG, ZIP.
     */
    public function subirAdjunto(Request $request, Obra $obra): JsonResponse
    {
        $request->validate([
            'archivo' => ['required', 'file', 'max:20480', 'mimes:pdf,xlsx,xls,doc,docx,dwg,jpg,jpeg,png,zip'],
            'tipo' => ['required', 'in:foto,plano,documento,contrato'],
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->store('obras/'.$obra->id, 'public');

        $adjunto = ObraAdjunto::create([
            'obra_id' => $obra->id,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,
            'tipo' => $request->input('tipo'),
            'mime' => $archivo->getClientMimeType(),
            'subido_por' => $request->user()->id,
        ]);

        return response()->json(['ok' => true, 'mensaje' => 'Archivo subido correctamente.', 'data' => $adjunto]);
    }

    public function eliminarAdjunto(ObraAdjunto $adjunto): JsonResponse
    {
        Storage::disk('public')->delete($adjunto->ruta);
        $adjunto->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Adjunto eliminado correctamente.']);
    }
}