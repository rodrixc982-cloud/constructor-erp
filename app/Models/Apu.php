<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Análisis de Precios Unitarios. Todos los cálculos de costo se
 * exponen como accessors para que estén siempre sincronizados con
 * las líneas de materiales/mano de obra/equipos/maquinaria asociadas,
 * sin necesidad de recalcular manualmente ni guardar valores obsoletos.
 */
class Apu extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'apus';

    protected $fillable = [
        'codigo', 'descripcion', 'unidad', 'rendimiento',
        'porcentaje_herramientas', 'porcentaje_costos_indirectos',
        'porcentaje_utilidad', 'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'rendimiento' => 'decimal:4',
            'porcentaje_herramientas' => 'decimal:2',
            'porcentaje_costos_indirectos' => 'decimal:2',
            'porcentaje_utilidad' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('apu');
    }

    public function materiales()
    {
        return $this->hasMany(ApuMaterial::class);
    }

    public function manoObra()
    {
        return $this->hasMany(ApuManoObra::class);
    }

    public function equipos()
    {
        return $this->hasMany(ApuEquipo::class);
    }

    public function maquinarias()
    {
        return $this->hasMany(ApuMaquinaria::class);
    }

    /** Costo de materiales por unidad de APU, incluyendo desperdicio. */
    public function getCostoMaterialesAttribute(): float
    {
        return $this->materiales->sum(fn ($m) => $m->cantidad * (1 + $m->desperdicio_pct / 100) * $m->precio_unitario);
    }

    /** Costo de mano de obra por unidad de APU. */
    public function getCostoManoObraAttribute(): float
    {
        return $this->manoObra->sum(fn ($m) => $m->cantidad * $m->costo_unitario);
    }

    /** Costo de herramientas, calculado como % de la mano de obra (práctica estándar). */
    public function getCostoHerramientasAttribute(): float
    {
        return $this->costo_mano_obra * ($this->porcentaje_herramientas / 100);
    }

    public function getCostoEquiposAttribute(): float
    {
        return $this->equipos->sum(fn ($e) => $e->cantidad * $e->costo_unitario);
    }

    public function getCostoMaquinariaAttribute(): float
    {
        return $this->maquinarias->sum(fn ($m) => $m->cantidad * $m->costo_unitario);
    }

    /** Suma de materiales + mano de obra + herramientas + equipos + maquinaria. */
    public function getCostoDirectoAttribute(): float
    {
        return $this->costo_materiales + $this->costo_mano_obra + $this->costo_herramientas
            + $this->costo_equipos + $this->costo_maquinaria;
    }

    public function getCostoIndirectoAttribute(): float
    {
        return $this->costo_directo * ($this->porcentaje_costos_indirectos / 100);
    }

    public function getMontoUtilidadAttribute(): float
    {
        return ($this->costo_directo + $this->costo_indirecto) * ($this->porcentaje_utilidad / 100);
    }

    /** Precio unitario final de venta de esta partida. */
    public function getPrecioUnitarioAttribute(): float
    {
        return $this->costo_directo + $this->costo_indirecto + $this->monto_utilidad;
    }

    /**
     * Resumen completo listo para JSON, usado en la calculadora en vivo del front-end.
     */
    public function resumenCostos(): array
    {
        return [
            'costo_materiales' => round($this->costo_materiales, 4),
            'costo_mano_obra' => round($this->costo_mano_obra, 4),
            'costo_herramientas' => round($this->costo_herramientas, 4),
            'costo_equipos' => round($this->costo_equipos, 4),
            'costo_maquinaria' => round($this->costo_maquinaria, 4),
            'costo_directo' => round($this->costo_directo, 4),
            'costo_indirecto' => round($this->costo_indirecto, 4),
            'monto_utilidad' => round($this->monto_utilidad, 4),
            'precio_unitario' => round($this->precio_unitario, 4),
        ];
    }
}
