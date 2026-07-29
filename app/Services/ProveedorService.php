<?php

namespace App\Services;

use App\Repositories\Contracts\ProveedorRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProveedorService
{
    public function __construct(protected ProveedorRepositoryInterface $repositorio)
    {
    }

    public function listar(array $filtros = [], int $porPagina = 15)
    {
        return $this->repositorio->paginate($porPagina, $filtros);
    }

    public function crear(array $datos): Model
    {
        return $this->repositorio->create($datos);
    }

    public function actualizar(int $id, array $datos): Model
    {
        return $this->repositorio->update($id, $datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->repositorio->delete($id);
    }

    public function restaurar(int $id): bool
    {
        return $this->repositorio->restore($id);
    }

    public function obtener(int $id): Model
    {
        return $this->repositorio->findOrFail($id);
    }
}
