<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kegiatan;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'fasilitas_id',
        'tipe_sewa_id',
        'kegiatan_id',
        'tanggal_sewa',
        'tanggal_selesai',
        'durasi_hari',
        'total_harga',
        'status_booking',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function tipeSewa()
    {
        return $this->belongsTo(TipeSewa::class, 'tipe_sewa_id');
    }
}
