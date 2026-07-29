<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApuMaterial extends Model
{
    protected $table = 'apu_materiales';

    protected $fillable = ['apu_id', 'material_id', 'cantidad', 'desperdicio_pct', 'precio_unitario'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:4', 'desperdicio_pct' => 'decimal:2', 'precio_unitario' => 'decimal:4'];
    }

    public function apu() { return $this->belongsTo(Apu::class); }
    public function material() { return $this->belongsTo(Material::class); }
}
