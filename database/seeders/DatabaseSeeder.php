<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

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

        $hasKegiatanStatus = Schema::hasColumn('kegiatan', 'status');

        foreach ($kegiatanList as $kegiatan) {
            $attributes = [
                'deskripsi' => $kegiatan['deskripsi'],
            ];

            if ($hasKegiatanStatus) {
                $attributes['status'] = $kegiatan['status'];
            }

            Kegiatan::firstOrCreate(
                ['nama_kegiatan' => $kegiatan['nama_kegiatan']],
                $attributes
            );
        }

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'nama' => 'Test User',
                'password' => 'password',
                'no_hp' => '081234567890',
                'alamat' => 'Alamat testing',
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin.dummy@example.com'],
            [
                'nama' => 'Admin Dummy',
                'password' => 'password',
                'no_hp' => '081200000002',
                'alamat' => 'Admin dummy untuk testing',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $now = now();

        DB::table('kategori')->updateOrInsert(
            ['nama_kategori' => 'Kategori Dummy'],
            [
                'deskripsi' => 'Kategori contoh untuk kebutuhan testing.',
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $kategoriDummyId = DB::table('kategori')
            ->where('nama_kategori', 'Kategori Dummy')
            ->value('id');

        if ($kategoriDummyId) {
            DB::table('fasilitas')->updateOrInsert(
                ['nama_fasilitas' => 'Fasilitas Dummy'],
                [
                    'kategori_id' => $kategoriDummyId,
                    'deskripsi' => 'Fasilitas contoh untuk testing dashboard dan booking.',
                    'kapasitas' => '100 orang',
                    'spesifikasi' => 'Dummy spec: kursi, meja, proyektor.',
                    'status_fasilitas' => 'available',
                    'gambar_fasilitas' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
