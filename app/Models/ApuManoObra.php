<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApuManoObra extends Model
{
    protected $table = 'apu_mano_obra';

    protected $fillable = ['apu_id', 'mano_obra_id', 'cantidad', 'costo_unitario'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:4', 'costo_unitario' => 'decimal:4'];
    }

    public function apu() { return $this->belongsTo(Apu::class); }
    public function manoObra() { return $this->belongsTo(ManoObra::class, 'mano_obra_id'); }
}
