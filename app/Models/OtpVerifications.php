<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerifications extends Model
{
    protected $table = 'otp_verifications';

    const MAX_ATTEMPTS = 3;

    protected $fillable = ['no_hp', 'kode', 'attempt_count', 'expired_at', 'verified_at'];

    protected $casts = [
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Scope untuk OTP yang masih berlaku
    public function scopeValid($query)
    {
        return $query->whereNull('verified_at')
                     ->where('expired_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    public function isMaxAttempts(): bool
    {
        return $this->attempt_count >= self::MAX_ATTEMPTS;
    }

    public function incrementAttempt(): void
    {
        $this->increment('attempt_count');
    }
}