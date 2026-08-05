<?php
// app/Models/Categoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Categoria extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'color',
        'icono',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'color', 'icono', 'descripcion', 'estado'])
            ->logOnlyDirty()
            ->useLogName('categorias');
    }

    public function materiales()
    {
        return $this->hasMany(Material::class);
    }
}