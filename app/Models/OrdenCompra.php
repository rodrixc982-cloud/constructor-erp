<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenCompra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordenes_compra';

    protected $fillable = ['codigo', 'proveedor_id', 'presupuesto_id', 'fecha', 'estado', 'observaciones', 'usuario_id'];

    protected function casts(): array { return ['fecha' => 'date']; }

    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function presupuesto() { return $this->belongsTo(Presupuesto::class); }
    public function usuario() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrdenCompraItem::class); }

    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn ($i) => $i->cantidad * $i->precio_unitario);
    }
}
