<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'kode_pembayaran',
        'booking_id',
        'metode_pembayaran',
        'bukti_pembayaran',
        'jumlah_pembayaran',
        'status_pembayaran',
        'alasan_penolakan',
        'tanggal_pembayaran',
    ];

    public function getBuktiPembayaranUrlAttribute(): string
    {
        $bukti = (string) ($this->attributes['bukti_pembayaran'] ?? '');

        if ($bukti === '') {
            return '';
        }

        if (filter_var($bukti, FILTER_VALIDATE_URL)) {
            $path = parse_url($bukti, PHP_URL_PATH);

            if (is_string($path) && $path !== '') {
                $storagePosition = strpos($path, '/storage/');

                if ($storagePosition !== false) {
                    return substr($path, $storagePosition);
                }
            }

            return $bukti;
        }

        if (str_starts_with($bukti, '/storage/') || str_starts_with($bukti, 'storage/')) {
            return '/' . ltrim($bukti, '/');
        }

        return '/storage/' . ltrim($bukti, '/');
    }
}
