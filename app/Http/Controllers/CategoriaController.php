<?php
// app/Http/Controllers/CategoriaController.php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Mostrar la vista principal de categorías
     */
    public function index()
    {
        // Verificar permisos si es necesario
        // $this->authorize('ver_categorias');
        
        return view('categorias.index');
    }

    /**
     * Obtener datos para DataTable (versión mejorada con manejo de errores)
     */
    public function datos()
    {
        try {
            Log::info('=== INICIANDO CARGA DE CATEGORÍAS ===');
            
            // Verificar conexión a la base de datos
            try {
                DB::connection()->getPdo();
                Log::info('Conexión a BD exitosa');
            } catch (\Exception $e) {
                Log::error('Error de conexión a BD: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Error de conexión a la base de datos',
                    'message' => $e->getMessage()
                ], 500);
            }

            // Verificar que la tabla existe
            if (!Schema::hasTable('categorias')) {
                Log::error('La tabla categorias no existe');
                return response()->json([
                    'error' => 'La tabla categorías no existe en la base de datos. Ejecuta: php artisan migrate'
                ], 500);
            }

            // Obtener datos con caché
            $categorias = Cache::remember('categorias_datos', 3600, function () {
                return Categoria::withCount('materiales')
                    ->orderBy('nombre')
                    ->get()
                    ->map(function ($categoria) {
                        return [
                            'id' => $categoria->id,
                            'nombre' => $categoria->nombre,
                            'descripcion' => $categoria->descripcion,
                            'color' => $categoria->color ?? '#2a5298',
                            'icono' => $categoria->icono ?? 'fa-cubes',
                            'estado' => (bool) $categoria->estado,
                            'materiales_count' => $categoria->materiales_count ?? 0,
                            'created_at' => $categoria->created_at?->format('d/m/Y H:i'),
                        ];
                    });
            });

            Log::info('Categorías encontradas: ' . $categorias->count());
            Log::info('=== CARGA DE CATEGORÍAS COMPLETADA ===');

            return response()->json([
                'success' => true,
                'data' => $categorias,
                'total' => $categorias->count()
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error de consulta SQL: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ' . json_encode($e->getBindings()));
            
            return response()->json([
                'error' => 'Error de consulta en la base de datos',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error general al obtener categorías: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Error al cargar las categorías',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una categoría específica
     */
    public function show($id)
    {
        try {
            $categoria = Categoria::withCount('materiales')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'descripcion' => $categoria->descripcion,
                    'color' => $categoria->color,
                    'icono' => $categoria->icono,
                    'estado' => (bool) $categoria->estado,
                    'materiales_count' => $categoria->materiales_count,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener categoría: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Categoría no encontrada'
            ], 404);
        }
    }

    /**
     * Crear nueva categoría
     */
    public function store(Request $request)
    {
        Log::info('Intentando crear categoría', $request->all());

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[a-f0-9]{6}$/i',
            'icono' => 'nullable|string|max:50',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $categoria = Categoria::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'color' => $request->color ?? '#2a5298',
                'icono' => $request->icono ?? 'fa-cubes',
                'estado' => $request->estado ?? 1,
            ]);

            Log::info('Categoría creada', [
                'categoria_id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();
            
            // Limpiar caché
            Cache::forget('categorias_datos');

            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría creada exitosamente',
                'data' => $categoria
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear categoría: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'nombre' => "required|string|max:100|unique:categorias,nombre,{$id}",
                'descripcion' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:7|regex:/^#[a-f0-9]{6}$/i',
                'icono' => 'nullable|string|max:50',
                'estado' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            
            $categoria->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'color' => $request->color ?? '#2a5298',
                'icono' => $request->icono ?? 'fa-cubes',
                'estado' => $request->estado ?? 1,
            ]);

            Log::info('Categoría actualizada', [
                'categoria_id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();
            
            // Limpiar caché
            Cache::forget('categorias_datos');

            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar categoría: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al actualizar la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar categoría (Soft Delete)
     */
    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            
            // Verificar si tiene materiales asociados
            if ($categoria->materiales()->exists()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'No se puede eliminar la categoría porque tiene ' . $categoria->materiales()->count() . ' materiales asociados'
                ], 422);
            }

            DB::beginTransaction();
            
            $categoria->delete();
            
            Log::info('Categoría eliminada (soft delete)', [
                'categoria_id' => $id,
                'nombre' => $categoria->nombre,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();
            
            // Limpiar caché
            Cache::forget('categorias_datos');

            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar categoría: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al eliminar la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado de la categoría (Activar/Desactivar)
     */
    public function toggleEstado(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'estado' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $categoria->update([
                'estado' => $request->estado,
            ]);

            Log::info('Estado de categoría cambiado', [
                'categoria_id' => $categoria->id,
                'nuevo_estado' => $request->estado,
                'usuario_id' => auth()->id()
            ]);

            // Limpiar caché
            Cache::forget('categorias_datos');

            return response()->json([
                'success' => true,
                'mensaje' => 'Estado actualizado exitosamente',
                'data' => [
                    'id' => $categoria->id,
                    'estado' => (bool) $categoria->estado
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de categoría: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurar categoría eliminada (Soft Delete)
     */
    public function restore($id)
    {
        try {
            $categoria = Categoria::withTrashed()->findOrFail($id);
            
            if (!$categoria->trashed()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La categoría no está eliminada'
                ], 422);
            }

            DB::beginTransaction();
            
            $categoria->restore();
            
            Log::info('Categoría restaurada', [
                'categoria_id' => $id,
                'nombre' => $categoria->nombre,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();
            
            // Limpiar caché
            Cache::forget('categorias_datos');

            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría restaurada exitosamente',
                'data' => $categoria
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al restaurar categoría: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al restaurar la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener categorías para selector (combobox)
     */
    public function select(Request $request)
    {
        try {
            $search = $request->get('q', '');
            
            $categorias = Categoria::where('estado', 1)
                ->when($search, function ($query, $search) {
                    return $query->where('nombre', 'LIKE', "%{$search}%");
                })
                ->orderBy('nombre')
                ->limit(10)
                ->get(['id', 'nombre', 'color', 'icono']);
            
            return response()->json([
                'success' => true,
                'data' => $categorias
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener categorías para select: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar categorías'
            ], 500);
        }
    }
}