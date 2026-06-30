<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Token
    |--------------------------------------------------------------------------
    | Token dari provider WhatsApp Anda (Fonnte, Wablas, Kominfo, dll).
    | Isi di .env: WHATSAPP_TOKEN=your-token-here
    */
    'token' => env('WHATSAPP_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Endpoint
    |--------------------------------------------------------------------------
    | URL endpoint API provider Anda.
    |
    | Contoh per provider:
    |   Fonnte : https://api.fonnte.com/send
    |   Wablas : https://console.wablas.com/api/send-message
    |
    | Isi di .env: WHATSAPP_ENDPOINT=https://api.provider.com/send
    */
    'endpoint' => env('WHATSAPP_ENDPOINT', ''),

    /*
    |--------------------------------------------------------------------------
    | Nomor Pengirim
    |--------------------------------------------------------------------------
    | Nomor WhatsApp pengirim yang terdaftar di provider Anda.
    | Isi di .env: WHATSAPP_SENDER=628xxxxxxxxxx
    */
    'sender' => env('WHATSAPP_SENDER', ''),

];