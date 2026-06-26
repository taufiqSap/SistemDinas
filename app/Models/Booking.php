<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kegiatan;
use App\Models\Fasilitas;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
    'kode_booking',
    'user_id',
    'fasilitas_id',
    'waktu_mulai',
    'waktu_selesai',
    'kegiatan',
    'dokumen_pdf',
    'status_booking',
    'alasan_pembatalan',
    'alasan_penolakan',
];
protected $casts = [
    'waktu_mulai' => 'datetime',
    'waktu_selesai' => 'datetime',
];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}
