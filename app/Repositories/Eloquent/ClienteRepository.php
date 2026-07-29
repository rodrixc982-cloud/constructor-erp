<?php

namespace App\Repositories\Eloquent;

use App\Models\Cliente;
use App\Repositories\Contracts\ClienteRepositoryInterface;

class ClienteRepository extends EloquentRepository implements ClienteRepositoryInterface
{
    public function __construct(Cliente $model)
    {
        parent::__construct($model);
    }

    protected function aplicarFiltros($query, array $filtros)
    {
        if (! empty($filtros['buscar'])) {
            $texto = $filtros['buscar'];
            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('dni', 'like', "%{$texto}%")
                    ->orWhere('ruc', 'like', "%{$texto}%")
                    ->orWhere('email', 'like', "%{$texto}%");
            });
        }

        return $query;
    }
}
