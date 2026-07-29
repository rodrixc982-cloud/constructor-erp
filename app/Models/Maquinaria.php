<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maquinaria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'marca', 'placa', 'costo_hora', 'costo_dia', 'costo_mensual', 'disponible', 'estado', 'observaciones'];

    protected function casts(): array
    {
        return [
            'costo_hora' => 'decimal:2', 'costo_dia' => 'decimal:2', 'costo_mensual' => 'decimal:2',
            'disponible' => 'boolean', 'estado' => 'boolean',
        ];
    }
}
