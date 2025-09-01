<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $permissions = [
            ['name' => 'createUser', 'displayName' => 'Usuarios -> Crear nuevo usuario', 'description' => 'Crear nuevo usuario'],
            ['name' => 'editUser', 'displayName' => 'Usuarios -> Editar usuario', 'description' => 'Editar usuario existente'],
            ['name' => 'deleteUser', 'displayName' => 'Usuarios -> Eliminar usuario', 'description' => 'Eliminar usuario'],
            ['name' => 'viewUser', 'displayName' => 'Usuarios -> Ver detalles del usuario', 'description' => 'Ver detalles del usuario'],
            ['name' => 'manageRoles', 'displayName' => 'Roles -> Administrar roles y permisos', 'description' => 'Administrar roles y permisos'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['displayName' => 'Administrador']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['displayName' => 'Usuario']
        );

        $adminRole->permissions()->sync(Permission::all()->pluck('id'));
        $userRole->permissions()->sync(
            Permission::whereIn('name', ['viewUser'])->pluck('id')
        );
    }
}
