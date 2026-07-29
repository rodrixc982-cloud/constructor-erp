<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Categoria extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Especificar el nombre correcto de la tabla
    protected $table = 'categorias';

    protected $fillable = ['nombre', 'color', 'icono', 'descripcion', 'estado'];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('categorias');
    }

    public function materiales()
    {
        return $this->hasMany(Material::class);
    }
}