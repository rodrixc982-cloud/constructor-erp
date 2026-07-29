<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Cada fila es un movimiento del Kardex: entrada, salida, ajuste o
 * transferencia entre almacenes. Nunca se edita ni elimina un
 * movimiento ya registrado (principio contable de trazabilidad);
 * para corregir un error se genera un movimiento de ajuste inverso.
 */
class MovimientoInventario extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'material_id', 'almacen_id', 'almacen_destino_id', 'tipo', 'cantidad',
        'stock_anterior', 'stock_nuevo', 'motivo', 'obra_id', 'usuario_id', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'stock_anterior' => 'decimal:2',
            'stock_nuevo' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('inventario');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'almacen_destino_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
