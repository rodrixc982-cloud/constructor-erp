<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller implements HasMiddleware
{
    public function __construct()
    {
        // Los middlewares se definen en el método middleware() estático
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:usuarios.ver', only: ['index', 'datos', 'show']),
            new Middleware('can:usuarios.crear', only: ['store']),
            new Middleware('can:usuarios.editar', only: ['update']),
            new Middleware('can:usuarios.eliminar', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $roles = Role::all();
        return view('usuarios.index', compact('roles'));
    }

    public function datos(): JsonResponse
    {
        $usuarios = User::with('roles')->latest()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '—',
                'is_active' => $user->is_active,
                'roles' => $user->roles->pluck('name')->join(', '),
                'created_at' => $user->created_at->format('d/m/Y H:i'),
            ];
        });

        return response()->json(['data' => $usuarios]);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles');
        return response()->json([
            'data' => array_merge($user->toArray(), [
                'roles' => $user->roles->pluck('id')->toArray(),
            ])
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $datos = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:20'],
                'password' => ['required', 'string', 'min:8'],
                'is_active' => ['boolean'],
                'roles' => ['array'],
                'roles.*' => ['exists:roles,id'],
            ]);

            $user = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'phone' => $datos['phone'] ?? null,
                'password' => Hash::make($datos['password']),
                'is_active' => $datos['is_active'] ?? true,
            ]);

            // CORRECCIÓN: Asignar roles por ID correctamente
            if (!empty($datos['roles'])) {
                $roleNames = Role::whereIn('id', $datos['roles'])->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            }

            return response()->json([
                'ok' => true, 
                'mensaje' => 'Usuario creado correctamente.', 
                'data' => $user->load('roles')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $datos = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'phone' => ['nullable', 'string', 'max:20'],
                'password' => ['nullable', 'string', 'min:8'],
                'is_active' => ['boolean'],
                'roles' => ['array'],
                'roles.*' => ['exists:roles,id'],
            ]);

            $updateData = [
                'name' => $datos['name'],
                'email' => $datos['email'],
                'phone' => $datos['phone'] ?? null,
                'is_active' => $datos['is_active'] ?? true,
            ];

            if (!empty($datos['password'])) {
                $updateData['password'] = Hash::make($datos['password']);
            }

            $user->update($updateData);

            // CORRECCIÓN: Asignar roles por ID correctamente
            if (isset($datos['roles'])) {
                $roleNames = Role::whereIn('id', $datos['roles'])->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            }

            return response()->json([
                'ok' => true, 
                'mensaje' => 'Usuario actualizado correctamente.', 
                'data' => $user->load('roles')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            if ($user->id === auth()->id()) {
                return response()->json([
                    'ok' => false, 
                    'mensaje' => 'No puedes eliminar tu propio usuario.'
                ], 422);
            }

            $user->delete();

            return response()->json([
                'ok' => true, 
                'mensaje' => 'Usuario eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return response()->json([
                'ok' => true, 
                'mensaje' => 'Usuario restaurado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error al restaurar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}