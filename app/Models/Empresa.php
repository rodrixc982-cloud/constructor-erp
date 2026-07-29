<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Empresa extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'empresas';

    protected $fillable = [
        'logo', 'nombre', 'ruc', 'direccion', 'telefono', 'email',
        'pagina_web', 'redes_sociales', 'igv', 'moneda', 'firma', 'pie_pagina_pdf',
    ];

    protected function casts(): array
    {
        return [
            'redes_sociales' => 'array',
            'igv' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('empresa');
    }

    public function logoUrl(): string
    {
        return $this->logo ? asset('storage/'.$this->logo) : asset('images/logo-default.png');
    }
}
