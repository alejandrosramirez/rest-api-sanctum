<?php

namespace Database\Seeders\ApiAdmin;

use App\Enums\WebAdminRoles;
use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /** Permissions related to roles */
        Permission::create([
            'name' => 'roles_read',
            'guard_name' => 'web_admin',
            'description' => 'Leer roles',
        ]);
        Permission::create([
            'name' => 'roles_create',
            'guard_name' => 'web_admin',
            'description' => 'Crear roles',
        ]);
        Permission::create([
            'name' => 'roles_update',
            'guard_name' => 'web_admin',
            'description' => 'Editar roles',
        ]);
        Permission::create([
            'name' => 'roles_delete',
            'guard_name' => 'web_admin',
            'description' => 'Eliminar roles',
        ]);

        /** Permissions related to admins */
        Permission::create([
            'name' => 'admins_read',
            'guard_name' => 'web_admin',
            'description' => 'Leer administradores',
        ]);
        Permission::create([
            'name' => 'admins_create',
            'guard_name' => 'web_admin',
            'description' => 'Crear administradores',
        ]);
        Permission::create([
            'name' => 'admins_update',
            'guard_name' => 'web_admin',
            'description' => 'Editar administradores',
        ]);
        Permission::create([
            'name' => 'admins_delete',
            'guard_name' => 'web_admin',
            'description' => 'Eliminar administradores',
        ]);

        $role = Role::create([
            'name' => WebAdminRoles::ADMINISTRATOR,
            'guard_name' => 'web_admin',
            'description' => 'Administrador',
        ]);

        $role->givePermissionTo(Permission::where('guard_name', 'web_admin')->get());
    }
}
