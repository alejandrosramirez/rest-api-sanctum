<?php

namespace Database\Seeders\Api;

use App\Enums\WebRoles;
use App\Models\Authorization\Permission;
use App\Models\Authorization\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /* Permissions related to roles */
        Permission::create([
            'name' => 'roles_read',
            'guard_name' => 'web',
            'description' => 'Leer roles',
        ]);
        Permission::create([
            'name' => 'roles_create',
            'guard_name' => 'web',
            'description' => 'Crear roles',
        ]);
        Permission::create([
            'name' => 'roles_update',
            'guard_name' => 'web',
            'description' => 'Editar roles',
        ]);
        Permission::create([
            'name' => 'roles_delete',
            'guard_name' => 'web',
            'description' => 'Eliminar roles',
        ]);

        /* Permissions related to states */
        Permission::create([
            'name' => 'states_read',
            'guard_name' => 'web',
            'description' => 'Leer estados',
        ]);
        Permission::create([
            'name' => 'states_create',
            'guard_name' => 'web',
            'description' => 'Crear estados',
        ]);
        Permission::create([
            'name' => 'states_update',
            'guard_name' => 'web',
            'description' => 'Editar estados',
        ]);
        Permission::create([
            'name' => 'states_delete',
            'guard_name' => 'web',
            'description' => 'Eliminar estados',
        ]);

        /* Permissions related to users */
        Permission::create([
            'name' => 'users_read',
            'guard_name' => 'web',
            'description' => 'Leer usuarios',
        ]);
        Permission::create([
            'name' => 'users_create',
            'guard_name' => 'web',
            'description' => 'Crear usuarios',
        ]);
        Permission::create([
            'name' => 'users_update',
            'guard_name' => 'web',
            'description' => 'Editar usuarios',
        ]);
        Permission::create([
            'name' => 'users_delete',
            'guard_name' => 'web',
            'description' => 'Eliminar usuarios',
        ]);

        $role = Role::create([
            'name' => WebRoles::ADMINISTRATOR,
            'guard_name' => 'web',
            'description' => 'Administrador',
        ]);

        $role->givePermissionTo(Permission::where('guard_name', 'web')->get());
    }
}
