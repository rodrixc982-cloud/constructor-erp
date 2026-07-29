<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cliente extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'dni', 'ruc', 'nombre', 'empresa', 'direccion', 'departamento',
        'provincia', 'distrito', 'referencia', 'whatsapp', 'email',
        'observaciones', 'estado',
    ];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('clientes');
    }

    // Relación con Obras y Presupuestos se agrega en las Fases 4 y 5.

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
