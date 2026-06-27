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
    [
        'NIK' => '1234567890123456',
        'nama' => 'Admin Testing',
        'password' => bcrypt('password'), // ← HARUS DI-HASH
        'alamat' => 'Akun admin untuk testing',
        'role' => 'admin',
        'status' => 'aktif',
    ]
);
    }
}