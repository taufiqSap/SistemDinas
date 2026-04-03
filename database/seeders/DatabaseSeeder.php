<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
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
        // User::factory(10)->create();

        $kegiatanList = [
            [
                'nama_kegiatan' => 'Rapat / Meeting',
                'deskripsi' => 'Kegiatan pertemuan formal untuk koordinasi atau diskusi.',
                'status' => 'active',
            ],
            [
                'nama_kegiatan' => 'Pelatihan / Workshop',
                'deskripsi' => 'Kegiatan pembelajaran atau peningkatan kapasitas.',
                'status' => 'active',
            ],
            [
                'nama_kegiatan' => 'Acara Resmi / Seremonial',
                'deskripsi' => 'Kegiatan resmi seperti acara pemerintah atau seremonial.',
                'status' => 'active',
            ],
        ];

        foreach ($kegiatanList as $kegiatan) {
            Kegiatan::firstOrCreate(
                ['nama_kegiatan' => $kegiatan['nama_kegiatan']],
                [
                    'deskripsi' => $kegiatan['deskripsi'],
                    'status' => $kegiatan['status'],
                ]
            );
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
