<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin KMDGI',
                'email' => 'superadmin@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'super admin',
            ],
            [
                'name' => 'Admin KMDGI',
                'email' => 'admin@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Editor KMDGI',
                'email' => 'editor@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'editor',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}