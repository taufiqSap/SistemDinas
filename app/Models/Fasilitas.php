<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
