<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Presupuesto extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'codigo', 'cliente_id', 'obra_id', 'responsable_id', 'fecha', 'validez_dias',
        'moneda', 'igv', 'utilidad_pct', 'descuento_pct', 'estado', 'version',
        'presupuesto_padre_id', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'igv' => 'decimal:2',
            'utilidad_pct' => 'decimal:2',
            'descuento_pct' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('presupuestos');
    }

    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function obra() { return $this->belongsTo(Obra::class); }
    public function responsable() { return $this->belongsTo(User::class, 'responsable_id'); }
    public function padre() { return $this->belongsTo(Presupuesto::class, 'presupuesto_padre_id'); }
    public function versiones() { return $this->hasMany(Presupuesto::class, 'presupuesto_padre_id'); }

    public function partidas()
    {
        return $this->hasMany(PresupuestoPartida::class)->orderBy('orden');
    }

    public function gastos()
    {
        return $this->hasMany(PresupuestoGasto::class);
    }

    // --- Subtotales por categoría (basados en las partidas de APU que participan) ---

    public function getSubtotalPartidasAttribute(): float
    {
        return $this->partidas->sum(fn ($p) => $p->metrado * $p->precio_unitario);
    }

    public function getSubtotalGastosAttribute(): float
    {
        return $this->gastos->sum(fn ($g) => $g->cantidad * $g->precio_unitario);
    }

    public function getSubtotalPorTipoGastoAttribute(): array
    {
        return $this->gastos->groupBy('tipo')->map(fn ($grupo) => $grupo->sum(fn ($g) => $g->cantidad * $g->precio_unitario))->toArray();
    }

    /** Costo directo total: partidas (que ya incluyen materiales+MO+equipo+maquinaria) + otros gastos directos. */
    public function getCostoDirectoAttribute(): float
    {
        return $this->subtotal_partidas + $this->subtotal_gastos;
    }

    /**
     * El costo indirecto y utilidad ya vienen incluidos dentro del precio unitario
     * de cada partida (que proviene del APU). Aquí solo se aplican descuento e IGV
     * a nivel de presupuesto completo.
     */
    public function getDescuentoAttribute(): float
    {
        return $this->costo_directo * ($this->descuento_pct / 100);
    }

    public function getBaseImponibleAttribute(): float
    {
        return $this->costo_directo - $this->descuento;
    }

    public function getMontoIgvAttribute(): float
    {
        return $this->base_imponible * ($this->igv / 100);
    }

    public function getTotalGeneralAttribute(): float
    {
        return $this->base_imponible + $this->monto_igv;
    }

    public function resumenTotales(): array
    {
        return [
            'subtotal_partidas' => round($this->subtotal_partidas, 2),
            'subtotal_gastos' => round($this->subtotal_gastos, 2),
            'subtotal_por_tipo_gasto' => $this->subtotal_por_tipo_gasto,
            'costo_directo' => round($this->costo_directo, 2),
            'descuento' => round($this->descuento, 2),
            'base_imponible' => round($this->base_imponible, 2),
            'monto_igv' => round($this->monto_igv, 2),
            'total_general' => round($this->total_general, 2),
        ];
    }
}
