<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeSewa extends Model
{
    protected $table = 'tipe_sewa';

    protected $fillable = [
        'nama_tipe',
        'deskripsi',
    ];

    public function hargaSewas()
    {
        return $this->hasMany(HargaSewa::class, 'tipe_sewa_id');
    }
}
