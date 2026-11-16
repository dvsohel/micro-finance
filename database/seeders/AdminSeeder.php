<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'superadmin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $admin->syncRoles('Super Admin');
    }
}
