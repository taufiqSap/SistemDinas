<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPhones;
use App\Models\OtpVerifications;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'nik'           => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:users,nik'],
        'nama'          => ['required', 'string', 'max:255'],
        'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'alamat'        => ['required', 'string'],
        'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        'jenis_daftar'  => ['required', 'in:perorangan,lembaga'],
        'nama_lembaga'  => ['nullable', 'string', 'max:255', 'required_if:jenis_daftar,lembaga'],
    ]);

    $user = User::create([
        'nik'           => $request->nik,
        'nama'          => $request->nama,
        'email'         => $request->email,
        'password'      => Hash::make($request->password),
        'alamat'        => $request->alamat,
        'jenis_daftar'  => $request->jenis_daftar,
        'nama_lembaga'  => $request->jenis_daftar === 'lembaga' ? $request->nama_lembaga : null,
        'status'        => 'nonaktif',
    ]);

    // Simpan user_id di session untuk digunakan di halaman kirim OTP
    session(['pending_user_id' => $user->id]);

    return redirect()->route('kirim.otp')
                     ->with('status', 'Akun berhasil dibuat. Silakan masukkan nomor HP untuk verifikasi.');
}
}