<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * API REST de Clientes para futuras apps móviles, protegida con
 * Sanctum. Los tokens se generan desde Perfil > API Tokens (a agregar
 * en el front) o vía `php artisan tinker` -> $user->createToken('movil').
 */
class ClienteApiController extends Controller
{
    public function index()
    {
        return Cliente::activos()->paginate(20);
    }

    public function show(Cliente $cliente)
    {
        return $cliente;
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:15'],
            'ruc' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
        ]);

        return response()->json(Cliente::create($datos), 201);
    }
}
