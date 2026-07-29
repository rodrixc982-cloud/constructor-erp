<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('empresa.editar');
    }

    public function rules(): array
    {
        $empresaId = \App\Models\Empresa::first()?->id;

        return [
            'logo' => ['nullable', 'image', 'max:2048'],
            'nombre' => ['required', 'string', 'max:255'],
            'ruc' => ['required', 'string', 'max:20', Rule::unique('empresas', 'ruc')->ignore($empresaId)],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'pagina_web' => ['nullable', 'url', 'max:255'],
            'redes_sociales' => ['nullable', 'array'],
            'redes_sociales.facebook' => ['nullable', 'url'],
            'redes_sociales.instagram' => ['nullable', 'url'],
            'redes_sociales.tiktok' => ['nullable', 'url'],
            'igv' => ['required', 'numeric', 'min:0', 'max:100'],
            'moneda' => ['required', 'string', 'max:10'],
            'firma' => ['nullable', 'image', 'max:2048'],
            'pie_pagina_pdf' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruc.unique' => 'Ya existe una empresa registrada con este RUC.',
        ];
    }
}
