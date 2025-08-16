<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@user.com',
            'password' => Hash::make('p@ssw0rd!'),
        ]);
    }
}
