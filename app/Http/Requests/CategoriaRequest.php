<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $accion = $this->isMethod('POST') ? 'categorias.crear' : 'categorias.editar';

        return $this->user()->can($accion);
    }

    public function rules(): array
    {
        $categoriaId = $this->route('categoria')?->id;

        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('categorias', 'nombre')->ignore($categoriaId)],
            'color' => ['required', 'string', 'max:20'],
            'icono' => ['required', 'string', 'max:60'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['boolean'],
        ];
    }
}
