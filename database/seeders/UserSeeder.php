<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crea un usuario de ejemplo por cada rol para poder probar
 * el sistema de permisos inmediatamente después del seed.
 * Contraseña por defecto para TODOS: "Password123!" (cambiar en producción).
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Administrador', 'Administrador', 'Gerente', 'Ingeniero',
            'Arquitecto', 'Presupuestista', 'Supervisor', 'Compras',
            'Almacén', 'Contabilidad', 'Cliente',
        ];

        foreach ($roles as $rol) {
            $email = Str::slug($rol).'@constructor-erp.test';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $rol,
                    'password' => Hash::make('Password123!'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$rol]);
        }
    }
}
