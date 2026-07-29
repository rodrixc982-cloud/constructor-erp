<?php

namespace App\Providers;

use App\Repositories\Contracts\CategoriaRepositoryInterface;
use App\Repositories\Contracts\ClienteRepositoryInterface;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Contracts\ProveedorRepositoryInterface;
use App\Repositories\Eloquent\CategoriaRepository;
use App\Repositories\Eloquent\ClienteRepository;
use App\Repositories\Eloquent\MaterialRepository;
use App\Repositories\Eloquent\ProveedorRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Centraliza el binding interfaz -> implementación del Repository Pattern.
 * Agregar aquí cada nuevo módulo (Obras, APU, Presupuestos, ...).
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ClienteRepositoryInterface::class => ClienteRepository::class,
        ProveedorRepositoryInterface::class => ProveedorRepository::class,
        CategoriaRepositoryInterface::class => CategoriaRepository::class,
        MaterialRepositoryInterface::class => MaterialRepository::class,
    ];

    public function register(): void
    {
        //
    }
}
