<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Material extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Especificar el nombre correcto de la tabla
    protected $table = 'materiales';

    protected $fillable = [
        'imagen', 'codigo', 'codigo_barras', 'nombre', 'marca', 'modelo',
        'categoria_id', 'proveedor_id', 'unidad', 'precio_compra',
        'precio_venta', 'stock', 'stock_minimo', 'iva', 'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'precio_compra' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'stock' => 'decimal:2',
            'stock_minimo' => 'decimal:2',
            'iva' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'precio_compra', 'precio_venta', 'stock', 'estado'])
            ->logOnlyDirty()
            ->useLogName('materiales');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function imagenUrl(): string
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : asset('images/material-default.png');
    }
}