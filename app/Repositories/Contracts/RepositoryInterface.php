<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Contrato base para todos los repositorios del sistema.
 * Cada módulo (Clientes, Proveedores, Materiales, etc.) implementa
 * esta interfaz, lo que permite cambiar la fuente de datos sin
 * tocar los Services ni Controllers (principio de inversión de dependencias).
 */
interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $filtros = []): LengthAwarePaginator;

    public function find(int $id): ?Model;

    public function findOrFail(int $id): Model;

    public function create(array $datos): Model;

    public function update(int $id, array $datos): Model;

    public function delete(int $id): bool;

    public function restore(int $id): bool;
}
