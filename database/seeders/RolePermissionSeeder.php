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
            ['name' => 'viewDashboard', 'displayName' => 'Dashboard -> Ver dashboard', 'description' => 'Ver detalles del dashboard principal'],

            ['name' => 'createUsers', 'displayName' => 'Usuarios -> Crear nuevo usuario', 'description' => 'Crear nuevo usuario'],
            ['name' => 'editUsers', 'displayName' => 'Usuarios -> Editar usuario', 'description' => 'Editar usuario existente'],
            ['name' => 'deleteUsers', 'displayName' => 'Usuarios -> Eliminar usuario', 'description' => 'Eliminar usuario'],
            ['name' => 'viewUsers', 'displayName' => 'Usuarios -> Ver detalles del usuario', 'description' => 'Ver detalles del usuario'],

            ['name' => 'viewRoles', 'displayName' => 'Roles -> Ver roles', 'description' => 'Ver roles y sus permisos'],
            ['name' => 'editRoles', 'displayName' => 'Roles -> Editar rol', 'description' => 'Editar roles existentes'],

            ['name' => 'viewClients', 'displayName' => 'Clientes -> Ver clientes', 'description' => 'Ver lista y detalles de clientes'],
            ['name' => 'createClients', 'displayName' => 'Clientes -> Crear cliente', 'description' => 'Crear nuevos clientes'],
            ['name' => 'editClients', 'displayName' => 'Clientes -> Editar cliente', 'description' => 'Editar clientes existentes'],
            ['name' => 'deleteClients', 'displayName' => 'Clientes -> Eliminar cliente', 'description' => 'Eliminar clientes'],

            ['name' => 'viewCategories', 'displayName' => 'Categorías -> Ver categorías', 'description' => 'Ver lista y detalles de categorías'],
            ['name' => 'createCategories', 'displayName' => 'Categorías -> Crear categoría', 'description' => 'Crear nuevas categorías'],
            ['name' => 'editCategories', 'displayName' => 'Categorías -> Editar categoría', 'description' => 'Editar categorías existentes'],
            ['name' => 'deleteCategories', 'displayName' => 'Categorías -> Eliminar categoría', 'description' => 'Eliminar categorías'],

            ['name' => 'viewSubcategories', 'displayName' => 'Subcategorías -> Ver subcategorías', 'description' => 'Ver lista y detalles de subcategorías'],
            ['name' => 'createSubcategories', 'displayName' => 'Subcategorías -> Crear subcategoría', 'description' => 'Crear nuevas subcategorías'],
            ['name' => 'editSubcategories', 'displayName' => 'Subcategorías -> Editar subcategoría', 'description' => 'Editar subcategorías existentes'],
            ['name' => 'deleteSubcategories', 'displayName' => 'Subcategorías -> Eliminar subcategoría', 'description' => 'Eliminar subcategorías'],

            ['name' => 'viewOrders', 'displayName' => 'Órdenes -> Ver órdenes', 'description' => 'Ver lista y detalles de órdenes'],
            ['name' => 'createOrders', 'displayName' => 'Órdenes -> Crear orden', 'description' => 'Crear nuevas órdenes'],
            ['name' => 'editOrders', 'displayName' => 'Órdenes -> Editar orden', 'description' => 'Editar órdenes existentes'],
            ['name' => 'deleteOrders', 'displayName' => 'Órdenes -> Eliminar orden', 'description' => 'Eliminar órdenes'],
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
            Permission::whereIn('name', ['viewUsers', 'viewClients'])->pluck('id')
        );
    }
}
