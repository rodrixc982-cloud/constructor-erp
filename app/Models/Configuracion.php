<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor', 'grupo'];

    public static function obtener(string $clave, mixed $default = null): mixed
    {
        return Cache::rememberForever("config:{$clave}", function () use ($clave, $default) {
            return static::where('clave', $clave)->value('valor') ?? $default;
        });
    }

    public static function establecer(string $clave, mixed $valor, string $grupo = 'general'): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor, 'grupo' => $grupo]);
        Cache::forget("config:{$clave}");
    }
}
