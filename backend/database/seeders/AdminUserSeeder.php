<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'abrahamcasanovac@outlook.com'],
            [
                'name' => 'Administrador',
                'email' => 'abrahamcasanovac@outlook.com',
                'password' => Hash::make('topsecret'),
                'role' => UserRoles::admin(),
            ]
        );
    }
}
