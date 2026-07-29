<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $accion = $this->isMethod('POST') ? 'clientes.crear' : 'clientes.editar';

        return $this->user()->can($accion);
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'dni' => ['nullable', 'string', 'max:15'],
            'ruc' => ['nullable', 'string', 'max:20'],
            'nombre' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'departamento' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'distrito' => ['nullable', 'string', 'max:100'],
            'referencia' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clientes', 'email')->ignore($clienteId)],
            'observaciones' => ['nullable', 'string'],
            'estado' => ['boolean'],
        ];
    }
}
