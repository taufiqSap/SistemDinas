<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerifications;
use App\Models\User;
use App\Models\UserPhones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerifyOtpController extends Controller
{
    /**
     * Tampilkan form input nomor HP untuk kirim OTP
     */
    public function showKirim()
    {
        if (!session()->has('pending_user_id')) {
            return redirect()->route('register')->withErrors(['error' => 'Silakan registrasi terlebih dahulu.']);
        }

        return view('auth.kirim-otp');
    }

    /**
     * Kirim OTP ke nomor HP yang diinput
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string', 'max:20', 'unique:user_phones,no_hp'],
        ]);

        $userId = session('pending_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'Sesi registrasi tidak valid. Silakan registrasi ulang.']);
        }

        // Buat user_phones
        UserPhones::create([
            'user_id' => $user->id,
            'no_hp' => $request->no_hp,
            'verified_at' => null,
        ]);

        // Generate OTP dengan durasi 60 detik
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpVerifications::create([
            'no_hp' => $request->no_hp,
            'kode' => $otpCode,
            'attempt_count' => 0,
            'expired_at' => Carbon::now()->addSeconds(60),
            'verified_at' => null,
        ]);

        Log::info("OTP untuk {$request->no_hp}: {$otpCode}");

        return redirect()->route('verifikasi.otp', ['no_hp' => $request->no_hp])
                         ->with('status', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Tampilkan form verifikasi OTP
     */
    public function show($no_hp)
    {
        // Reset counter resend di awal sesi verifikasi
        session(['otp_resend_count' => 0]);

        $userPhone = UserPhones::where('no_hp', $no_hp)->first();
        if (!$userPhone) {
            return redirect()->route('kirim.otp')->withErrors(['no_hp' => 'Nomor HP tidak terdaftar.']);
        }
        if ($userPhone->verified_at) {
            return redirect()->route('login')->with('status', 'Akun sudah terverifikasi.');
        }

        $otp = OtpVerifications::where('no_hp', $no_hp)
            ->whereNull('verified_at')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        // Jika tidak ada OTP yang valid, buat baru dengan durasi 60 detik
        if (!$otp) {
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $otp = OtpVerifications::create([
                'no_hp' => $no_hp,
                'kode' => $otpCode,
                'attempt_count' => 0,
                'expired_at' => Carbon::now()->addSeconds(60),
                'verified_at' => null,
            ]);
            Log::info("OTP baru untuk {$no_hp}: {$otpCode}");
        }

        return view('auth.verifikasi-otp', [
            'no_hp' => $no_hp,
            'expired_at' => $otp->expired_at,
        ]);
    }

    /**
     * Proses verifikasi OTP
     */
    public function verify(Request $request)
    {
        // Validasi: kode dikirim sebagai array 6 digit
        $request->validate([
            'no_hp' => ['required', 'string'],
            'kode'  => ['required', 'array', 'size:6'],
        ]);

        $no_hp = $request->no_hp;
        $kode = implode('', $request->kode); // Gabungkan 6 digit menjadi string

        // Cari OTP yang valid
        $otp = OtpVerifications::where('no_hp', $no_hp)
            ->where('kode', $kode)
            ->whereNull('verified_at')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            // Cari OTP yang masih berlaku tapi kode salah
            $existingOtp = OtpVerifications::where('no_hp', $no_hp)
                ->whereNull('verified_at')
                ->where('expired_at', '>', Carbon::now())
                ->first();

            if ($existingOtp) {
                $existingOtp->increment('attempt_count');
                if ($existingOtp->attempt_count >= 3) {
                    $existingOtp->update(['expired_at' => Carbon::now()]);
                    return back()->withErrors(['kode' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
                }
                return back()->withErrors(['kode' => 'Kode OTP salah. Sisa percobaan: ' . (3 - $existingOtp->attempt_count)]);
            } else {
                return back()->withErrors(['kode' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
            }
        }

        // OTP valid
        $otp->update(['verified_at' => Carbon::now()]);

        $userPhone = UserPhones::where('no_hp', $no_hp)->first();
        if ($userPhone) {
            $userPhone->update(['verified_at' => Carbon::now()]);
            $user = $userPhone->user;
            if ($user) {
                $user->update(['status' => 'aktif']);
            }
        }

        session()->forget(['pending_user_id', 'otp_resend_count']);

        return redirect()->route('login')->with('status', 'Akun berhasil diverifikasi. Silakan login.');
    }

    /**
     * Kirim ulang OTP dengan durasi berkurang
     */
    public function resend(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string'],
        ]);

        $no_hp = $request->no_hp;

        $userPhone = UserPhones::where('no_hp', $no_hp)->first();
        if (!$userPhone) {
            return back()->withErrors(['no_hp' => 'Nomor HP tidak terdaftar.']);
        }
        if ($userPhone->verified_at) {
            return redirect()->route('login')->with('status', 'Akun sudah terverifikasi.');
        }

        // Ambil counter resend dari session
        $resendCount = session('otp_resend_count', 0);
        $resendCount++;
        session(['otp_resend_count' => $resendCount]);

        // Tentukan durasi berdasarkan jumlah resend: 120, 90, 60 (minimum 60)
        $duration = max(60, 120 - ($resendCount - 1) * 30);

        // Kadaluarsakan OTP lama
        OtpVerifications::where('no_hp', $no_hp)
            ->whereNull('verified_at')
            ->update(['expired_at' => Carbon::now()]);

        // Generate OTP baru
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpVerifications::create([
            'no_hp' => $no_hp,
            'kode' => $otpCode,
            'attempt_count' => 0,
            'expired_at' => Carbon::now()->addSeconds($duration),
            'verified_at' => null,
        ]);

        Log::info("OTP resend #{$resendCount} untuk {$no_hp}: {$otpCode} (durasi {$duration}s)");

        return back()->with('status', "Kode OTP baru telah dikirim (berlaku {$duration} detik).");
    }
}