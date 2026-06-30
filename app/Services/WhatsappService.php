<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $token;
    private string $endpoint;
    private string $senderNumber;

    public function __construct()
    {
        $this->token        = config('whatsapp.token');
        $this->endpoint     = config('whatsapp.endpoint');
        $this->senderNumber = config('whatsapp.sender');
    }

    /**
     * Kirim pesan WhatsApp ke satu nomor.
     *
     * @param  string $phone   Nomor tujuan (format: 08xxx atau 628xxx)
     * @param  string $message Isi pesan
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        $phone = $this->normalizePhone($phone);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->endpoint, [
                    'target'  => $phone,
                    'message' => $message,
                    'sender'  => $this->senderNumber,
                ]);

            if ($response->successful()) {
                Log::info('[WhatsApp] Pesan terkirim', [
                    'phone'   => $phone,
                    'message' => $message,
                    'status'  => $response->status(),
                ]);
                return true;
            }

            Log::warning('[WhatsApp] Gagal kirim pesan', [
                'phone'    => $phone,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('[WhatsApp] Exception saat kirim pesan', [
                'phone'   => $phone,
                'error'   => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Normalisasi nomor HP ke format internasional (628xxx).
     * Mendukung format: 08xxx, 628xxx, +628xxx
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus semua non-digit

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        }

        return $phone;
    }
}