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

            ['name' => 'viewRole', 'displayName' => 'Roles -> Ver roles', 'description' => 'Ver roles y sus permisos'],
            ['name' => 'createRole', 'displayName' => 'Roles -> Crear rol', 'description' => 'Crear nuevos roles'],
            ['name' => 'editRole', 'displayName' => 'Roles -> Editar rol', 'description' => 'Editar roles existentes'],

            ['name' => 'viewPermission', 'displayName' => 'Permisos -> Ver permisos', 'description' => 'Ver lista de permisos'],
            ['name' => 'createPermission', 'displayName' => 'Permisos -> Crear permiso', 'description' => 'Crear nuevos permisos'],
            ['name' => 'editPermission', 'displayName' => 'Permisos -> Editar permiso', 'description' => 'Editar permisos existentes'],

            ['name' => 'viewClient', 'displayName' => 'Clientes -> Ver clientes', 'description' => 'Ver lista y detalles de clientes'],
            ['name' => 'createClient', 'displayName' => 'Clientes -> Crear cliente', 'description' => 'Crear nuevos clientes'],
            ['name' => 'editClient', 'displayName' => 'Clientes -> Editar cliente', 'description' => 'Editar clientes existentes'],
            ['name' => 'deleteClient', 'displayName' => 'Clientes -> Eliminar cliente', 'description' => 'Eliminar clientes'],

            ['name' => 'viewCategory', 'displayName' => 'Categorías -> Ver categorías', 'description' => 'Ver lista y detalles de categorías'],
            ['name' => 'createCategory', 'displayName' => 'Categorías -> Crear categoría', 'description' => 'Crear nuevas categorías'],
            ['name' => 'editCategory', 'displayName' => 'Categorías -> Editar categoría', 'description' => 'Editar categorías existentes'],
            ['name' => 'deleteCategory', 'displayName' => 'Categorías -> Eliminar categoría', 'description' => 'Eliminar categorías'],

            ['name' => 'viewSubcategory', 'displayName' => 'Subcategorías -> Ver subcategorías', 'description' => 'Ver lista y detalles de subcategorías'],
            ['name' => 'createSubcategory', 'displayName' => 'Subcategorías -> Crear subcategoría', 'description' => 'Crear nuevas subcategorías'],
            ['name' => 'editSubcategory', 'displayName' => 'Subcategorías -> Editar subcategoría', 'description' => 'Editar subcategorías existentes'],
            ['name' => 'deleteSubcategory', 'displayName' => 'Subcategorías -> Eliminar subcategoría', 'description' => 'Eliminar subcategorías'],

            ['name' => 'viewOrder', 'displayName' => 'Órdenes -> Ver órdenes', 'description' => 'Ver lista y detalles de órdenes'],
            ['name' => 'createOrder', 'displayName' => 'Órdenes -> Crear orden', 'description' => 'Crear nuevas órdenes'],
            ['name' => 'editOrder', 'displayName' => 'Órdenes -> Editar orden', 'description' => 'Editar órdenes existentes'],
            ['name' => 'deleteOrder', 'displayName' => 'Órdenes -> Eliminar orden', 'description' => 'Eliminar órdenes'],
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
            Permission::whereIn('name', ['viewUser', 'viewClient'])->pluck('id')
        );
    }
}
