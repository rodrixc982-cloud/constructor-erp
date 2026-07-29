<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraItem extends Model
{
    protected $table = 'orden_compra_items';
    protected $fillable = ['orden_compra_id', 'material_id', 'cantidad', 'precio_unitario'];

    public function ordenCompra() { return $this->belongsTo(OrdenCompra::class); }
    public function material() { return $this->belongsTo(Material::class); }

    public function getSubtotalAttribute(): float { return $this->cantidad * $this->precio_unitario; }
}
