<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [   'NIK' => '1234567890123456',
                'nama' => 'Admin Testing',
                'password' => 'password',
                'no_hp' => '081200000001',
                'alamat' => 'Akun admin untuk testing',
                'role' => 'admin',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ],
             ['email' => 'taufiq@gmail.com'],
            [   'NIK' => '1234567890123457',
                'nama' => 'taufiq',
                'password' => 'password',
                'no_hp' => '08120003243001',
                'alamat' => 'Sentul',
                'role' => 'user',
                'status' => 'aktif',
                'jenis_daftar' => 'lembaga',
                'nama_lembaga' => 'Seni Budaya',
                'email_verified_at' => now(),
            ]
        );
    }
}