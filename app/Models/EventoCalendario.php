<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoCalendario extends Model
{
    use HasFactory;

    protected $table = 'eventos_calendario';

    protected $fillable = ['titulo', 'tipo', 'fecha_inicio', 'fecha_fin', 'obra_id', 'usuario_id', 'descripcion'];

    protected function casts(): array
    {
        return ['fecha_inicio' => 'datetime', 'fecha_fin' => 'datetime'];
    }

    public function obra() { return $this->belongsTo(Obra::class); }
    public function usuario() { return $this->belongsTo(User::class); }
}
