<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Especificar el nombre correcto de la tabla
    protected $table = 'proveedores';

    protected $fillable = [
        'ruc', 'nombre', 'productos', 'contacto', 'telefono', 'email',
        'direccion', 'banco', 'cuenta', 'cci', 'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('proveedores');
    }

    public function materiales()
    {
        return $this->hasMany(Material::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}