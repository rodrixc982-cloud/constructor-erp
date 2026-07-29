<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        $accion = $this->isMethod('POST') ? 'materiales.crear' : 'materiales.editar';

        return $this->user()->can($accion);
    }

    public function rules(): array
    {
        $materialId = $this->route('material')?->id;

        return [
            'imagen' => ['nullable', 'image', 'max:2048'],
            'codigo' => ['nullable', 'string', 'max:50', Rule::unique('materiales', 'codigo')->ignore($materialId)],
            'codigo_barras' => ['nullable', 'string', 'max:50', Rule::unique('materiales', 'codigo_barras')->ignore($materialId)],
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:100'],
            'modelo' => ['nullable', 'string', 'max:100'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'unidad' => ['required', 'string', 'max:20'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['required', 'numeric', 'min:0'],
            'iva' => ['required', 'numeric', 'min:0', 'max:100'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
