<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Crea los 11 roles del ERP y un set base de permisos por módulo.
 * Los permisos siguen el patrón "modulo.accion" (ej: presupuestos.crear)
 * para que sea fácil de extender a medida que se agregan módulos.
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Módulos del sistema. Cada uno genera automáticamente
     * los permisos ver / crear / editar / eliminar / exportar.
     */
    protected array $modulos = [
        'empresa', 'usuarios', 'roles', 'clientes', 'proveedores',
        'categorias', 'materiales', 'inventario', 'mano_obra',
        'equipos', 'maquinaria', 'obras', 'apu', 'presupuestos',
        'calculadora', 'compras', 'caja', 'facturacion', 'calendario',
        'reportes', 'auditoria', 'notificaciones', 'configuracion',
        'adjuntos',
    ];

    protected array $acciones = ['ver', 'crear', 'editar', 'eliminar', 'exportar'];

    public function run(): void
    {
        foreach ($this->modulos as $modulo) {
            foreach ($this->acciones as $accion) {
                Permission::firstOrCreate(['name' => "{$modulo}.{$accion}", 'guard_name' => 'web']);
            }
        }

        $roles = [
            'Super Administrador' => 'Acceso total al sistema, sin restricciones',
            'Administrador' => 'Gestión completa de módulos operativos',
            'Gerente' => 'Supervisión de obras, presupuestos y reportes',
            'Ingeniero' => 'Gestión técnica de obras y APU',
            'Arquitecto' => 'Gestión de diseño, obras y presupuestos',
            'Presupuestista' => 'Creación y edición de presupuestos y APU',
            'Supervisor' => 'Seguimiento de obras y mano de obra',
            'Compras' => 'Gestión de proveedores y órdenes de compra',
            'Almacén' => 'Gestión de inventario y materiales',
            'Contabilidad' => 'Gestión de caja, facturación y reportes financieros',
            'Cliente' => 'Acceso limitado a sus propias obras y presupuestos',
        ];

        foreach ($roles as $nombre => $descripcion) {
            Role::firstOrCreate(
                ['name' => $nombre, 'guard_name' => 'web'],
                ['description' => $descripcion]
            );
        }

        // Super Administrador y Administrador reciben todos los permisos.
        $todosLosPermisos = Permission::all();
        Role::findByName('Super Administrador')->syncPermissions($todosLosPermisos);
        Role::findByName('Administrador')->syncPermissions($todosLosPermisos);

        // Cliente: solo lectura de sus obras/presupuestos.
        Role::findByName('Cliente')->syncPermissions([
            'obras.ver', 'presupuestos.ver', 'facturacion.ver', 'calendario.ver',
        ]);

        // El resto de roles reciben "ver" de todo + permisos específicos de su área,
        // ajustables luego desde el panel de Configuración > Roles.
        $rolesOperativos = ['Gerente', 'Ingeniero', 'Arquitecto', 'Presupuestista', 'Supervisor', 'Compras', 'Almacén', 'Contabilidad'];
        foreach ($rolesOperativos as $nombreRol) {
            $role = Role::findByName($nombreRol);
            $permisosVer = Permission::where('name', 'like', '%.ver')->pluck('name')->toArray();
            $role->syncPermissions($permisosVer);
        }

        // Permisos de edición específicos por área.
        Role::findByName('Presupuestista')->givePermissionTo(['presupuestos.crear', 'presupuestos.editar', 'apu.crear', 'apu.editar']);
        Role::findByName('Almacén')->givePermissionTo(['inventario.crear', 'inventario.editar', 'materiales.crear', 'materiales.editar']);
        Role::findByName('Compras')->givePermissionTo(['compras.crear', 'compras.editar', 'proveedores.crear', 'proveedores.editar']);
        Role::findByName('Contabilidad')->givePermissionTo(['caja.crear', 'caja.editar', 'facturacion.crear', 'facturacion.editar']);
    }
}
