<?php

namespace Database\Seeders\Api;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Miguel Alejandro',
            'lastname' => 'Salgado Ramírez',
            'phone' => '3330204397',
            'email' => 'alejandrosram@outlook.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('administrator');

        User::factory(35)->create()->each(function (User $user) {
            $user->assignRole('administrator');
        });
    }
}
