<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoPartida extends Model
{
    protected $table = 'presupuesto_partidas';

    protected $fillable = ['presupuesto_id', 'apu_id', 'orden', 'descripcion', 'unidad', 'metrado', 'precio_unitario'];

    protected function casts(): array
    {
        return ['metrado' => 'decimal:4', 'precio_unitario' => 'decimal:4'];
    }

    public function presupuesto() { return $this->belongsTo(Presupuesto::class); }
    public function apu() { return $this->belongsTo(Apu::class); }

    public function getSubtotalAttribute(): float
    {
        return $this->metrado * $this->precio_unitario;
    }
}
