<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * La empresa se maneja como singleton: siempre se edita el primer
 * (y único) registro. Si no existe, se crea vacío para poder mostrarlo.
 */
class EmpresaController extends Controller implements HasMiddleware
{
    public function __construct()
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:empresa.ver', only: ['edit']),
            new Middleware('can:empresa.editar', only: ['update']),
        ];
    }

    public function edit(): View
    {
        $empresa = Empresa::first() ?? new Empresa;

        return view('empresa.edit', compact('empresa'));
    }

    public function update(EmpresaRequest $request): RedirectResponse
    {
        $empresa = Empresa::first() ?? new Empresa;
        $datos = $request->validated();

        // Procesar logo
        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $datos['logo'] = $request->file('logo')->store('empresa', 'public');
        }

        // Procesar firma
        if ($request->hasFile('firma')) {
            if ($empresa->firma) {
                Storage::disk('public')->delete($empresa->firma);
            }
            $datos['firma'] = $request->file('firma')->store('empresa/firmas', 'public');
        }

        // Convertir redes sociales a JSON si viene como array
        if (isset($datos['redes_sociales']) && is_array($datos['redes_sociales'])) {
            $datos['redes_sociales'] = json_encode($datos['redes_sociales']);
        }

        $empresa->fill($datos)->save();

        return back()->with('status', 'Datos de la empresa actualizados correctamente.');
    }
}