<?php

namespace Database\Seeders\ApiAdmin;

use Illuminate\Database\Seeder;

class ApiAdminDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminsSeeder::class,
        ]);
    }
}
