<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApuMaquinaria extends Model
{
    protected $table = 'apu_maquinarias';

    protected $fillable = ['apu_id', 'maquinaria_id', 'cantidad', 'costo_unitario'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:4', 'costo_unitario' => 'decimal:4'];
    }

    public function apu() { return $this->belongsTo(Apu::class); }
    public function maquinaria() { return $this->belongsTo(Maquinaria::class); }
}
