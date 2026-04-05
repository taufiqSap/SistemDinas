<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaSewa extends Model
{
    protected $table = 'harga_sewa';

    protected $fillable = [
        'fasilitas_id',
        'tipe_sewa_id',
        'harga',
    ];
}
