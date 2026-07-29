<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApuEquipo extends Model
{
    protected $table = 'apu_equipos';

    protected $fillable = ['apu_id', 'equipo_id', 'cantidad', 'costo_unitario'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:4', 'costo_unitario' => 'decimal:4'];
    }

    public function apu() { return $this->belongsTo(Apu::class); }
    public function equipo() { return $this->belongsTo(Equipo::class); }
}
