<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraAdjunto extends Model
{
    protected $fillable = ['obra_id', 'nombre_original', 'ruta', 'tipo', 'mime', 'subido_por'];

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }
}
