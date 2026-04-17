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
}
