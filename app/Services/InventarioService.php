<?php

namespace App\Services;

use App\Models\Almacen;
use App\Models\Material;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventarioService
{
    public function registrarEntrada(int $materialId, int $almacenId, float $cantidad, ?string $motivo, int $usuarioId, ?string $observaciones = null): MovimientoInventario
    {
        return DB::transaction(function () use ($materialId, $almacenId, $cantidad, $motivo, $usuarioId, $observaciones) {
            $material = Material::lockForUpdate()->findOrFail($materialId);
            $stockAnterior = $material->stock;

            $material->increment('stock', $cantidad);
            $this->actualizarStockAlmacen($almacenId, $materialId, $cantidad);

            return MovimientoInventario::create([
                'material_id' => $materialId,
                'almacen_id' => $almacenId,
                'tipo' => 'entrada',
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockAnterior + $cantidad,
                'motivo' => $motivo,
                'usuario_id' => $usuarioId,
                'observaciones' => $observaciones,
            ]);
        });
    }

    public function registrarSalida(int $materialId, int $almacenId, float $cantidad, ?string $motivo, int $usuarioId, ?string $observaciones = null): MovimientoInventario
    {
        return DB::transaction(function () use ($materialId, $almacenId, $cantidad, $motivo, $usuarioId, $observaciones) {
            $material = Material::lockForUpdate()->findOrFail($materialId);

            if ($material->stock < $cantidad) {
                throw ValidationException::withMessages(['cantidad' => 'No hay stock suficiente para esta salida.']);
            }

            $stockAnterior = $material->stock;
            $material->decrement('stock', $cantidad);
            $this->actualizarStockAlmacen($almacenId, $materialId, -$cantidad);

            return MovimientoInventario::create([
                'material_id' => $materialId,
                'almacen_id' => $almacenId,
                'tipo' => 'salida',
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockAnterior - $cantidad,
                'motivo' => $motivo,
                'usuario_id' => $usuarioId,
                'observaciones' => $observaciones,
            ]);
        });
    }

    public function transferir(int $materialId, int $almacenOrigenId, int $almacenDestinoId, float $cantidad, int $usuarioId, ?string $observaciones = null): MovimientoInventario
    {
        return DB::transaction(function () use ($materialId, $almacenOrigenId, $almacenDestinoId, $cantidad, $usuarioId, $observaciones) {
            $this->actualizarStockAlmacen($almacenOrigenId, $materialId, -$cantidad);
            $this->actualizarStockAlmacen($almacenDestinoId, $materialId, $cantidad);

            $material = Material::findOrFail($materialId);

            return MovimientoInventario::create([
                'material_id' => $materialId,
                'almacen_id' => $almacenOrigenId,
                'almacen_destino_id' => $almacenDestinoId,
                'tipo' => 'transferencia',
                'cantidad' => $cantidad,
                'stock_anterior' => $material->stock,
                'stock_nuevo' => $material->stock,
                'usuario_id' => $usuarioId,
                'observaciones' => $observaciones,
            ]);
        });
    }

    public function ajustar(int $materialId, int $almacenId, float $nuevaCantidad, int $usuarioId, ?string $observaciones = null): MovimientoInventario
    {
        return DB::transaction(function () use ($materialId, $almacenId, $nuevaCantidad, $usuarioId, $observaciones) {
            $material = Material::lockForUpdate()->findOrFail($materialId);
            $stockAnterior = $material->stock;
            $diferencia = $nuevaCantidad - $stockAnterior;

            $material->update(['stock' => $nuevaCantidad]);
            $this->actualizarStockAlmacen($almacenId, $materialId, $diferencia);

            return MovimientoInventario::create([
                'material_id' => $materialId,
                'almacen_id' => $almacenId,
                'tipo' => 'ajuste',
                'cantidad' => abs($diferencia),
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $nuevaCantidad,
                'motivo' => 'Ajuste manual de inventario',
                'usuario_id' => $usuarioId,
                'observaciones' => $observaciones,
            ]);
        });
    }

    protected function actualizarStockAlmacen(int $almacenId, int $materialId, float $delta): void
    {
        $pivot = Almacen::find($almacenId)->materiales()->where('material_id', $materialId)->first();

        if ($pivot) {
            Almacen::find($almacenId)->materiales()->updateExistingPivot($materialId, [
                'stock' => max(0, $pivot->pivot->stock + $delta),
            ]);
        } else {
            Almacen::find($almacenId)->materiales()->attach($materialId, ['stock' => max(0, $delta)]);
        }
    }

    public function kardex(int $materialId)
    {
        return MovimientoInventario::with(['almacen', 'almacenDestino', 'usuario'])
            ->where('material_id', $materialId)
            ->latest()
            ->get();
    }

    // --- CORRECCIÓN AQUÍ ---
    public function materialesStockBajo()
    {
        // Esto buscará los materiales donde el campo 'stock' sea menor o igual a 'stock_minimo'.
        // Si no hiciste el Paso 2 (agregar columna 'stock_minimo'), cambia esto por:
        // return Material::where('stock', '<=', 5)->where('estado', true)->get();
        
        return Material::whereColumn('stock', '<=', 'stock_minimo')
                       ->where('estado', true)
                       ->get();
    }
}