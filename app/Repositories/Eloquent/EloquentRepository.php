<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Implementación Eloquent genérica. Los repositorios concretos
 * (ClienteRepository, MaterialRepository, ...) extienden esta clase
 * y solo sobrescriben lo que necesitan personalizar (ej: filtros de búsqueda).
 */
abstract class EloquentRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    public function paginate(int $perPage = 15, array $filtros = []): LengthAwarePaginator
    {
        return $this->aplicarFiltros($this->model->newQuery(), $filtros)
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Hook para que cada repositorio aplique sus propios filtros de búsqueda.
     * Por defecto no filtra nada.
     */
    protected function aplicarFiltros($query, array $filtros)
    {
        return $query;
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $datos): Model
    {
        return $this->model->create($datos);
    }

    public function update(int $id, array $datos): Model
    {
        $registro = $this->findOrFail($id);
        $registro->update($datos);

        return $registro;
    }

    public function delete(int $id): bool
    {
        return (bool) $this->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return (bool) $this->model->withTrashed()->findOrFail($id)->restore();
    }
}
