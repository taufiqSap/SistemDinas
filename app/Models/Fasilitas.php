<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';

    protected $fillable = [
        'kategori_id',
        'nama_fasilitas',
        'deskripsi',
        'kapasitas',
        'spesifikasi',
        'status_fasilitas',
        'gambar_fasilitas',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function hargaSewas()
    {
        return $this->hasMany(HargaSewa::class, 'fasilitas_id');
    }

    public function getGambarFasilitasUrlAttribute(): string
    {
        $gambar = (string) ($this->attributes['gambar_fasilitas'] ?? '');

        if ($gambar === '') {
            return 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1400&q=80';
        }

        if (filter_var($gambar, FILTER_VALIDATE_URL)) {
            $path = parse_url($gambar, PHP_URL_PATH);

            if (is_string($path) && $path !== '') {
                $storagePosition = strpos($path, '/storage/');

                if ($storagePosition !== false) {
                    return substr($path, $storagePosition);
                }
            }

            return $gambar;
        }

        if (str_starts_with($gambar, '/storage/') || str_starts_with($gambar, 'storage/')) {
            return '/' . ltrim($gambar, '/');
        }

        return Storage::disk('public')->url($gambar);
    }
}
