<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Hak Akses Manajemen Internal
            [
                'name' => 'Super Admin KMDGI',
                'email' => 'superadmin@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'super admin',
                'kategori' => 'Internal',
            ],
            [
                'name' => 'Admin KMDGI',
                'email' => 'admin@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'kategori' => 'Internal',
            ],
            [
                'name' => 'Editor KMDGI',
                'email' => 'editor@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'kategori' => 'Internal',
            ],
            
            // Sampel Akun Peserta: DELEGASI (Menu Delegasi & Submisi Karya MUNCUL)
            [
                'name' => 'Muhammad Akbar',
                'email' => 'akbar@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'peserta',
                'kategori' => 'Delegasi',
                'peran_delegasi' => 'Ketua',
                'institusi' => 'Universitas Pendidikan Indonesia',
                'auth_code' => 'KMDGI-UPI-2026',
                'tanggal_lahir' => '2004-05-12',
                'no_hp' => '081234567890',
            ],

            // Sampel Akun Peserta: UMUM (Menu Delegasi & Submisi Karya TERSEMBUNYI)
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@kmdgi.com',
                'password' => Hash::make('password123'),
                'role' => 'peserta',
                'kategori' => 'Umum',
                'peran_delegasi' => null,
                'institusi' => null,
                'auth_code' => null,
                'tanggal_lahir' => '2003-08-20',
                'no_hp' => '089876543210',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}