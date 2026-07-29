<?php

namespace App\Repositories\Eloquent;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;

class MaterialRepository extends EloquentRepository implements MaterialRepositoryInterface
{
    public function __construct(Material $model)
    {
        parent::__construct($model);
    }

    protected function aplicarFiltros($query, array $filtros)
    {
        $query->with(['categoria', 'proveedor']);

        if (! empty($filtros['buscar'])) {
            $texto = $filtros['buscar'];
            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('codigo', 'like', "%{$texto}%")
                    ->orWhere('marca', 'like', "%{$texto}%");
            });
        }

        if (! empty($filtros['categoria_id'])) {
            $query->where('categoria_id', $filtros['categoria_id']);
        }

        if (! empty($filtros['stock_bajo'])) {
            $query->stockBajo();
        }

        return $query;
    }

    /**
     * Genera el siguiente código correlativo con formato MAT-000001.
     */
    public function siguienteCodigo(): string
    {
        $ultimo = $this->model->withTrashed()->orderByDesc('id')->first();
        $siguienteNumero = $ultimo ? ($ultimo->id + 1) : 1;

        return 'MAT-'.str_pad((string) $siguienteNumero, 6, '0', STR_PAD_LEFT);
    }
}
