<?php

namespace Database\Seeders\ApiAdmin;

use Illuminate\Database\Seeder;

class ApiAdminDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminsSeeder::class,
        ]);
    }
}
