<?php

namespace Database\Seeders;

use Database\Seeders\Api\ApiDatabaseSeeder;
use Database\Seeders\ApiAdmin\ApiAdminDatabaseSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ApiAdminDatabaseSeeder::class,
            ApiDatabaseSeeder::class,
        ]);
    }
}
