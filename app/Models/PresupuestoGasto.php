<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoGasto extends Model
{
    protected $table = 'presupuesto_gastos';

    protected $fillable = ['presupuesto_id', 'tipo', 'concepto', 'cantidad', 'precio_unitario'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:4', 'precio_unitario' => 'decimal:4'];
    }

    public function presupuesto() { return $this->belongsTo(Presupuesto::class); }

    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * $this->precio_unitario;
    }
}
