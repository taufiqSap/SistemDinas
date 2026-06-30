<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerifications;
use App\Models\User;
use App\Models\UserPhones;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyOtpController extends Controller
{
    public function __construct(private WhatsAppService $whatsApp) {}

    /**
     * Tampilkan form input nomor HP untuk kirim OTP.
     */
    public function showKirim()
    {
        if (!session()->has('pending_user_id')) {
            return redirect()->route('register')->withErrors(['error' => 'Silakan registrasi terlebih dahulu.']);
        }

        return view('auth.kirim-otp');
    }

    /**
     * Simpan nomor HP, generate OTP, dan kirim via WhatsApp.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string', 'max:20', 'unique:user_phones,no_hp'],
        ]);

        $userId = session('pending_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'Sesi registrasi tidak valid. Silakan registrasi ulang.']);
        }

        UserPhones::create([
            'user_id'     => $user->id,
            'no_hp'       => $request->no_hp,
            'verified_at' => null,
        ]);

        $otpCode = $this->generateOtp();

        OtpVerifications::create([
            'no_hp'         => $request->no_hp,
            'kode'          => $otpCode,
            'attempt_count' => 0,
            'expired_at'    => Carbon::now()->addSeconds(60),
            'verified_at'   => null,
        ]);

        // Kirim OTP via WhatsApp
        $this->sendOtpWhatsApp($request->no_hp, $otpCode);

        return redirect()
            ->route('verifikasi.otp', ['no_hp' => $request->no_hp])
            ->with('status', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Tampilkan form verifikasi OTP.
     */
    public function show($no_hp)
    {
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

        // Jika tidak ada OTP yang valid, buat dan kirim OTP baru
        if (!$otp) {
            $otpCode = $this->generateOtp();

            $otp = OtpVerifications::create([
                'no_hp'         => $no_hp,
                'kode'          => $otpCode,
                'attempt_count' => 0,
                'expired_at'    => Carbon::now()->addSeconds(60),
                'verified_at'   => null,
            ]);

            $this->sendOtpWhatsApp($no_hp, $otpCode);
        }

        return view('auth.verifikasi-otp', [
            'no_hp'      => $no_hp,
            'expired_at' => $otp->expired_at,
        ]);
    }

    /**
     * Proses verifikasi kode OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string'],
            'kode'  => ['required', 'array', 'size:6'],
        ]);

        $no_hp = $request->no_hp;
        $kode  = implode('', $request->kode);

        $otp = OtpVerifications::where('no_hp', $no_hp)
            ->where('kode', $kode)
            ->whereNull('verified_at')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            $existingOtp = OtpVerifications::where('no_hp', $no_hp)
                ->whereNull('verified_at')
                ->where('expired_at', '>', Carbon::now())
                ->first();

            if ($existingOtp) {
                $existingOtp->increment('attempt_count');

                if ($existingOtp->attempt_count >= OtpVerifications::MAX_ATTEMPTS) {
                    $existingOtp->update(['expired_at' => Carbon::now()]);
                    return back()->withErrors(['kode' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
                }

                $sisa = OtpVerifications::MAX_ATTEMPTS - $existingOtp->attempt_count;
                return back()->withErrors(['kode' => "Kode OTP salah. Sisa percobaan: {$sisa}"]);
            }

            return back()->withErrors(['kode' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        // OTP valid — tandai terverifikasi
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
     * Kirim ulang OTP dengan durasi berkurang tiap pengiriman.
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

        $resendCount = session('otp_resend_count', 0) + 1;
        session(['otp_resend_count' => $resendCount]);

        // Durasi berkurang: 120s → 90s → 60s (minimum 60s)
        $duration = max(60, 120 - ($resendCount - 1) * 30);

        // Kadaluarsakan OTP lama
        OtpVerifications::where('no_hp', $no_hp)
            ->whereNull('verified_at')
            ->update(['expired_at' => Carbon::now()]);

        // Generate & kirim OTP baru
        $otpCode = $this->generateOtp();

        OtpVerifications::create([
            'no_hp'         => $no_hp,
            'kode'          => $otpCode,
            'attempt_count' => 0,
            'expired_at'    => Carbon::now()->addSeconds($duration),
            'verified_at'   => null,
        ]);

        $this->sendOtpWhatsApp($no_hp, $otpCode);

        Log::info("[OTP] Resend #{$resendCount} untuk {$no_hp} (durasi {$duration}s)");

        return back()->with('status', "Kode OTP baru telah dikirim (berlaku {$duration} detik).");
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Generate kode OTP 6 digit dengan zero-padding.
     */
    private function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Kirim kode OTP via WhatsApp.
     */
    private function sendOtpWhatsApp(string $noHp, string $otpCode): void
    {
        $message = "Kode OTP Anda adalah: *{$otpCode}*\n\nKode berlaku selama 60 detik. Jangan bagikan kode ini kepada siapapun.";

        $sent = $this->whatsApp->send($noHp, $message);

        if (!$sent) {
            Log::warning("[OTP] Gagal kirim OTP via WhatsApp ke {$noHp}");
        }
    }
}