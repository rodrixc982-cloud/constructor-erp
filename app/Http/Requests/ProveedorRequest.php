<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $accion = $this->isMethod('POST') ? 'proveedores.crear' : 'proveedores.editar';

        return $this->user()->can($accion);
    }

    public function rules(): array
    {
        $proveedorId = $this->route('proveedor')?->id;

        return [
            'ruc' => ['required', 'string', 'max:20', Rule::unique('proveedores', 'ruc')->ignore($proveedorId)],
            'nombre' => ['required', 'string', 'max:255'],
            'productos' => ['nullable', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'banco' => ['nullable', 'string', 'max:100'],
            'cuenta' => ['nullable', 'string', 'max:50'],
            'cci' => ['nullable', 'string', 'max:30'],
            'estado' => ['boolean'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
