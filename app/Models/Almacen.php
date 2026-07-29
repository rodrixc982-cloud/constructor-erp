<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use HasFactory, SoftDeletes;

    // Especificar el nombre correcto de la tabla
    protected $table = 'almacenes';

    protected $fillable = ['nombre', 'ubicacion', 'responsable', 'estado'];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'almacen_material')->withPivot('stock')->withTimestamps();
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}