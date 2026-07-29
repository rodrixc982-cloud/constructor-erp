<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'marca', 'costo_alquiler_dia', 'costo_mantenimiento', 'disponible', 'estado', 'observaciones'];

    protected function casts(): array
    {
        return ['costo_alquiler_dia' => 'decimal:2', 'costo_mantenimiento' => 'decimal:2', 'disponible' => 'boolean', 'estado' => 'boolean'];
    }
}
