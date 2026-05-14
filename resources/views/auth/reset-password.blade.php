<x-guest-layout>
    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .input-field {
                width: 100%;
                border-radius: 0.75rem;
                border: 1px solid #e5e7eb;
                background: rgba(249, 250, 251, 0.75);
                color: #111827;
                height: 3rem;
                font-size: 0.95rem;
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
            .label-text {
                color: #374151;
                font-weight: 600;
            }
            .card-title {
                font-size: 1.9rem;
                font-weight: 800;
                color: #ffffff;
            }
            .card-subtitle {
                color: rgba(255,255,255,0.8);
            }
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
                <img alt="Landmark Kota Blitar" class="h-full w-full object-cover" src="{{ asset('images/bg.png') }}" />
                <div class="absolute inset-0 bg-gradient-to-br from-[#C6352F]/65 via-black/50 to-[#0F172A]/80"></div>
                <div class="animate-pulse-slow pointer-events-none absolute -bottom-16 -left-16 h-72 w-72 rounded-full bg-[#FFD700]/10 blur-3xl"></div>
                <div class="pointer-events-none absolute right-10 top-10 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md animate-fadein">
                <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="bg-gradient-to-r from-[#C6352F] to-[#a82b25] px-8 pb-6 pt-8 text-center">
                        <div class="mb-3 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white/15 backdrop-blur">
                            <span class="material-symbols-outlined text-3xl text-white">lock_reset</span>
                        </div>
                        <h1 class="card-title">Reset Kata Sandi</h1>
                        <p class="mt-1 text-sm card-subtitle">Masukkan kata sandi baru untuk akun Anda.</p>
                    </div>

                    <div class="border-b border-gray-100 px-8">
                        <div class="border-b-[3px] border-[#C6352F] py-3 text-center text-sm font-bold tracking-wide text-[#C6352F]">
                            Buat Password Baru
                        </div>
                    </div>

                    <div class="px-8 py-6">
                        <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-5">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                            <div class="animate-fadein-delay flex flex-col gap-1.5">
                                <label for="password" class="label-text">Password Baru</label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">lock</span>
                                    <input
                                        id="password-input"
                                        name="password"
                                        type="password"
                                        class="input-field"
                                        placeholder="Masukkan kata sandi baru"
                                        required
                                        autocomplete="new-password"
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
                                <p class="text-xs text-gray-500">Gunakan minimal 8 karakter, kombinasi huruf dan angka.</p>
                            </div>

                            <div class="animate-fadein-delay2 flex flex-col gap-1.5">
                                <label for="password_confirmation" class="label-text">Konfirmasi Password</label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">lock_open</span>
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="input-field"
                                        placeholder="Ulangi kata sandi baru"
                                        required
                                        autocomplete="new-password"
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600" />
                            </div>

                            <div class="animate-fadein-delay2 pt-1">
                                <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    Reset Kata Sandi
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-8 py-4 text-center">
                        <p class="text-sm text-gray-500">
                            Kembali ke halaman
                            <a href="{{ route('login') }}" class="ml-1 font-bold text-[#C6352F] hover:underline">login</a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>
