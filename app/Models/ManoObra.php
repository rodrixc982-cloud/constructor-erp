<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManoObra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mano_obras';

    protected $fillable = ['nombre', 'especialidad', 'documento', 'telefono', 'tipo_costo', 'costo', 'estado', 'observaciones'];

    protected function casts(): array
    {
        return ['costo' => 'decimal:2', 'estado' => 'boolean'];
    }
}
