<x-guest-layout>
    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .input-field {
                width: 100%;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                background: rgba(249, 250, 251, 0.7);
                color: #111827;
                height: 3rem;
                font-size: 0.875rem;
                transition: all 0.2s;
                padding-left: 2.75rem;
                padding-right: 1rem;
            }
            .input-field:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(198, 53, 47, 0.15);
                border-color: #c6352f;
            }
            .input-field::placeholder { color: #9ca3af; }
            @keyframes fadein {
                0% { opacity: 0; transform: translateY(16px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            @keyframes pulse-slow {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
            .animate-fadein { animation: fadein 0.5s ease both; }
            .animate-fadein-delay { animation: fadein 0.5s 0.15s ease both; }
            .animate-fadein-delay2 { animation: fadein 0.5s 0.3s ease both; }
            .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
        </style>
    @endpush

    <div class="bg-[#F5F5F0] text-gray-900 antialiased">
        <main class="relative flex min-h-[calc(100vh-80px)] items-center justify-center p-4 py-12">
            <div class="absolute inset-0 z-0">
                <img alt="Landmark Kota Blitar" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAlko62n4s7VrNC6p8PpEWWWFGsEaYKBfqid6hCkaJjQ4LIi9M6Nru52MFUiiMRSAW54w1rmSUARMv8KsCJ0CPXhn_xbbq5KDHT2WrLrc9lYg-_GHGLNxjTHpPTrucbgtW-CvM2CScsCYRv5Wr70I0eDE6qFp5nXRkbnYYt7tA1CFhvDgPYNGcUtyhUXIUVMYv7FI97QjKOdCGAQoMBuF7m-JeNaUkqveSxl1bCw5BUQKy08aM_fpdGIcTuWATUVVmY5A9BYqxPeqA" />
                <div class="absolute inset-0 bg-gradient-to-br from-[#C6352F]/65 via-black/50 to-[#0F172A]/80"></div>
                <div class="animate-pulse-slow pointer-events-none absolute -bottom-16 -left-16 h-72 w-72 rounded-full bg-[#FFD700]/10 blur-3xl"></div>
                <div class="pointer-events-none absolute right-10 top-10 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md animate-fadein">
                <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="bg-gradient-to-r from-[#C6352F] to-[#a82b25] px-8 pb-6 pt-8 text-center">
                        <div class="mb-3 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white/15 backdrop-blur">
                            <span class="material-symbols-outlined text-3xl text-white">lock_person</span>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-white">Selamat Datang</h1>
                        <p class="mt-1 text-sm text-white/75">Masuk untuk menyewa aset kota Blitar</p>
                    </div>

                    <div class="border-b border-gray-100 px-8">
                        <div class="border-b-[3px] border-[#C6352F] py-3 text-center text-sm font-bold tracking-wide text-[#C6352F]">
                            Masuk
                        </div>
                    </div>

                    <div class="px-8 py-6">
                        <x-auth-session-status class="mb-4 text-sm font-medium text-green-600" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                            @csrf

                            <div class="animate-fadein-delay flex flex-col gap-1.5">
                                <label for="email" class="text-sm font-semibold text-gray-800">Email</label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">person</span>
                                    <input id="email" name="email" type="email" class="input-field" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus autocomplete="username" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm" />
                            </div>

                            <div class="animate-fadein-delay flex flex-col gap-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="password" class="text-sm font-semibold text-gray-800">Kata Sandi</label>
                                    @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                        <a class="text-xs font-semibold text-[#C6352F] hover:underline" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
                                    @endif
                                </div>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">lock</span>
                                    <input id="password-input" name="password" type="password" class="input-field pe-11" placeholder="Masukkan kata sandi Anda" required autocomplete="current-password" />
                                    <button type="button" onclick="togglePassword()" class="absolute right-0 flex h-full items-center px-3.5 text-gray-400 transition-colors hover:text-gray-700">
                                        <span id="eye-icon" class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm" />
                            </div>

                            <div class="animate-fadein-delay2 flex items-center gap-2">
                                <input id="remember" type="checkbox" name="remember" class="h-4 w-4 cursor-pointer rounded border-gray-300 accent-[#C6352F]" />
                                <label for="remember" class="cursor-pointer select-none text-sm text-gray-600">Ingat saya</label>
                            </div>

                            <div class="animate-fadein-delay2 pt-1">
                                <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]">
                                    <span class="material-symbols-outlined text-[18px]">login</span>
                                    Masuk Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-8 py-4 text-center">
                        <p class="text-sm text-gray-500">
                            Belum punya akun?
                            @if (\Illuminate\Support\Facades\Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-1 font-bold text-[#C6352F] hover:underline">Daftar disini</a>
                            @endif
                        </p>
                    </div>
                </div>

                <p class="mt-5 text-center text-xs text-white/60">© 2025 Dinas Kebudayaan &amp; Pariwisata · Pemerintah Kota Blitar</p>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            function togglePassword() {
                const input = document.getElementById('password-input');
                const icon = document.getElementById('eye-icon');
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.textContent = input.type === 'password' ? 'visibility' : 'visibility_off';
            }
        </script>
    @endpush
</x-guest-layout>
