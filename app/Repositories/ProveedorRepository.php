<?php

namespace App\Repositories;

use App\Models\Proveedor;
use App\Repositories\Contracts\ProveedorRepositoryInterface;

class ProveedorRepository extends BaseRepository implements ProveedorRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Proveedor());
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters['buscar'])) {
            $buscar = $filters['buscar'];
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('ruc', 'LIKE', "%{$buscar}%")
                  ->orWhere('contacto', 'LIKE', "%{$buscar}%")
                  ->orWhere('email', 'LIKE', "%{$buscar}%");
            });
        }

        if (isset($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        return $query->orderBy('nombre')->paginate($perPage);
    }
}