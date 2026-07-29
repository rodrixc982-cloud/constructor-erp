<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DocumentoVenta extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Especificar el nombre correcto de la tabla
    protected $table = 'documentos_venta';

    protected $fillable = [
        'tipo', 'serie', 'numero', 'cliente_id', 'presupuesto_id', 'fecha',
        'subtotal', 'igv', 'total', 'estado', 'observaciones', 'usuario_id',
    ];

    protected function casts(): array
    {
        return ['fecha' => 'date', 'subtotal' => 'decimal:2', 'igv' => 'decimal:2', 'total' => 'decimal:2'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('facturacion');
    }

    public function cliente() 
    { 
        return $this->belongsTo(Cliente::class); 
    }
    
    public function presupuesto() 
    { 
        return $this->belongsTo(Presupuesto::class); 
    }
    
    public function usuario() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function getNumeroCompletoAttribute(): string
    {
        return ($this->serie ? $this->serie.'-' : '').$this->numero;
    }
}