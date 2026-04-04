<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Testing',
                'password' => 'password',
                'no_hp' => '081200000001',
                'alamat' => 'Akun admin untuk testing',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}