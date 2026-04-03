<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}