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
     
        $this->call(AdminUserSeeder::class);


        User::updateOrCreate(
            ['email' => 'admin.dummy@example.com'],
            [
                'nama' => 'Admin Dummy',
                'password' => 'password', 
                'no_hp' => '081200000002',
                'alamat' => 'Admin dummy untuk testing',
                'role' => 'admin',
                'status' => 'aktif', 
                'email_verified_at' => now(),
            ]
        );
    }
}