<?php

namespace Database\Seeders\Api;

use Illuminate\Database\Seeder;

class ApiDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatesSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
