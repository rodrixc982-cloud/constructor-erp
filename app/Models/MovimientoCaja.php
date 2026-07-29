<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MovimientoCaja extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'movimientos_caja';

    protected $fillable = ['tipo', 'concepto', 'monto', 'fecha', 'presupuesto_id', 'cliente_id', 'proveedor_id', 'usuario_id', 'observaciones'];

    protected function casts(): array { return ['monto' => 'decimal:2', 'fecha' => 'date']; }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('caja');
    }

    public function presupuesto() { return $this->belongsTo(Presupuesto::class); }
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function usuario() { return $this->belongsTo(User::class); }
}
