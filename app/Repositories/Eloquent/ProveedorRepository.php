<?php

namespace App\Repositories\Eloquent;

use App\Models\Proveedor;
use App\Repositories\Contracts\ProveedorRepositoryInterface;

class ProveedorRepository extends EloquentRepository implements ProveedorRepositoryInterface
{
    public function __construct(Proveedor $model)
    {
        parent::__construct($model);
    }

    protected function aplicarFiltros($query, array $filtros)
    {
        if (! empty($filtros['buscar'])) {
            $texto = $filtros['buscar'];
            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('ruc', 'like', "%{$texto}%")
                    ->orWhere('contacto', 'like', "%{$texto}%");
            });
        }

        return $query;
    }
}
