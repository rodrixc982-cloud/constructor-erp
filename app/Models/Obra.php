<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Obra extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nombre', 'cliente_id', 'ubicacion', 'latitud', 'longitud', 'responsable_id',
        'fecha_inicio', 'fecha_fin_estimada', 'fecha_fin_real', 'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date', 'fecha_fin_estimada' => 'date', 'fecha_fin_real' => 'date',
            'latitud' => 'decimal:7', 'longitud' => 'decimal:7',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('obras');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function adjuntos()
    {
        return $this->hasMany(ObraAdjunto::class);
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }
}
