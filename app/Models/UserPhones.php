<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhones extends Model
{
    protected $table = 'user_phones';

    protected $fillable = ['user_id', 'no_hp', 'verified_at'];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}