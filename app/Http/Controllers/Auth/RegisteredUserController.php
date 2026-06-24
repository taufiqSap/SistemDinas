<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache; // Digunakan jika Anda menyimpan OTP di Cache
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register'); // Pastikan path view sesuai[cite: 5]
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
  public function store(Request $request): RedirectResponse
{
    $request->validate([
        'nik'    => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:'.User::class],
        'nama'   => ['required', 'string', 'max:255'],
        'email'  => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'no_hp'  => ['required', 'string', 'max:20', 'unique:'.User::class],
        'alamat' => ['required', 'string'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'jenis_daftar' => ['required', 'in:perorangan,lembaga'],
        'nama_lembaga' => ['nullable', 'string', 'max:255', 'required_if:jenis_daftar,lembaga'],
    ]);

    $user = User::create([
        'nik'    => $request->nik,
        'nama'   => $request->nama,
        'email'  => $request->email,
        'no_hp'  => $request->no_hp,
        'alamat' => $request->alamat,
        'password' => Hash::make($request->password),
        'jenis_daftar' => $request->jenis_daftar,
        'nama_lembaga' => $request->jenis_daftar === 'lembaga' ? $request->nama_lembaga : null,
    ]);

    return redirect()->route('login')->with('status', 'Akun berhasil dibuat. Silakan masuk.');
}
}