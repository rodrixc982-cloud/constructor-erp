<?php

namespace App\Services;

use App\Repositories\Contracts\ClienteRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Capa de Service: concentra la lógica de negocio del módulo Clientes,
 * dejando al Controller solo la orquestación HTTP y al Repository
 * solo el acceso a datos.
 */
class ClienteService
{
    public function __construct(protected ClienteRepositoryInterface $repositorio)
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
