<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPhones;
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
        $admins = [
            [
                'email' => 'adminselaras@gmail.com',
                'nik' => '1234567890123456',
                'nama' => 'Admin',
                'password' => 'password',
                'alamat' => 'Akun admin 1 untuk testing',
                'role' => 'admin',
                'status' => 'aktif',
                
                'phone' => '+6285737644100',
            ],
            [
                'email' => 'adminselaras2@gmail.com',
                'nik' => '1234567890123457',
                'nama' => 'Admin 2',
                'password' => 'password',
                'alamat' => 'Akun admin 2 untuk testing',
                'role' => 'admin',
                'status' => 'aktif',
                'phone' => '+62881026145594',
            ],
        ];

        foreach ($admins as $data) {
            $phone = $data['phone'] ?? null;
            unset($data['phone']);

            $user = User::updateOrCreate([
                'email' => $data['email'],
            ], $data);

            if ($phone) {
                UserPhones::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'no_hp' => $phone,
                    'verified_at' => now(),
                ]);
            }
        }
    }
}