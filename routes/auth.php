<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyOtpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Route untuk kirim OTP
    Route::get('/kirim-otp', [VerifyOtpController::class, 'showKirim'])->name('kirim.otp');
    Route::post('/kirim-otp', [VerifyOtpController::class, 'sendOtp'])->name('kirim.otp.send');

    // Route untuk verifikasi OTP
    Route::get('/verifikasi-otp/{no_hp}', [VerifyOtpController::class, 'show'])->name('verifikasi.otp');
    Route::post('/verifikasi-otp', [VerifyOtpController::class, 'verify'])->name('verifikasi.otp.verify');
    Route::post('/verifikasi-otp/resend', [VerifyOtpController::class, 'resend'])->name('verifikasi.otp.resend');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});