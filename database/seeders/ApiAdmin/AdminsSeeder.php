<?php

namespace Database\Seeders\ApiAdmin;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'Administrador',
            'lastname' => 'Pruebas',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('administrator');
    }
}
