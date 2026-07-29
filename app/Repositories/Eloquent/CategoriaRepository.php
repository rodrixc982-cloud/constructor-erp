<?php

namespace App\Repositories\Eloquent;

use App\Models\Categoria;
use App\Repositories\Contracts\CategoriaRepositoryInterface;

class CategoriaRepository extends EloquentRepository implements CategoriaRepositoryInterface
{
    public function __construct(Categoria $model)
    {
        parent::__construct($model);
    }

    protected function aplicarFiltros($query, array $filtros)
    {
        if (! empty($filtros['buscar'])) {
            $query->where('nombre', 'like', '%'.$filtros['buscar'].'%');
        }

        return $query;
    }
}
