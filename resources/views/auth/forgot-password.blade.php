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
            @keyframes slideInDown {
                0% { opacity: 0; transform: scale(0.9) translateY(-30px); }
                100% { opacity: 1; transform: scale(1) translateY(0); }
            }
            @keyframes slideOutUp {
                0% { opacity: 1; transform: scale(1) translateY(0); }
                100% { opacity: 0; transform: scale(0.9) translateY(-30px); }
            }
            @keyframes scaleCheckmark {
                0% { transform: scale(0) rotate(-45deg); opacity: 0; }
                50% { transform: scale(1.2) rotate(0deg); }
                100% { transform: scale(1) rotate(0deg); opacity: 1; }
            }
            .toast-enter { animation: slideInDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
            .toast-exit { animation: slideOutUp 0.4s ease-in forwards; }
            .checkmark-animate { animation: scaleCheckmark 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); }
        </style>
    @endpush

    <div class="bg-[#F5F5F0] text-gray-900 antialiased">
        <!-- Success Modal Notification -->
        @if (session('status'))
            <div id="successToast" class="toast-enter fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
                <div class="relative flex flex-col items-center gap-4 rounded-2xl bg-white px-8 py-8 shadow-2xl max-w-sm w-full">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                        <svg class="checkmark-animate h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-800">Berhasil Terkirim!</p>
                        <p class="mt-2 text-sm text-gray-600">Periksa email Anda untuk tautan reset kata sandi</p>
                    </div>
                    <button onclick="closeToast()" class="mt-2 rounded-lg bg-green-50 px-6 py-2 text-sm font-semibold text-green-700 hover:bg-green-100 transition">
                        Tutup
                    </button>
                </div>
            </div>
        @endif

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
                            <span class="material-symbols-outlined text-3xl text-white">mail_lock</span>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-white">Lupa Kata Sandi?</h1>
                        <p class="mt-1 text-sm text-white/75">Kami akan mengirimkan tautan reset ke email Anda</p>
                    </div>

                    <div class="border-b border-gray-100 px-8">
                        <div class="border-b-[3px] border-[#C6352F] py-3 text-center text-sm font-bold tracking-wide text-[#C6352F]">
                            Reset Kata Sandi
                        </div>
                    </div>

                    <div class="px-8 py-6">
                        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
                            @csrf

                            <div class="animate-fadein-delay flex flex-col gap-1.5">
                                <label for="email" class="text-sm font-semibold text-gray-800">Email Address</label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">mail</span>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        class="input-field" 
                                        placeholder="Masukkan email Anda" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="email" 
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                                <p class="pl-1 text-xs text-gray-500">Masukkan email yang terdaftar di akun Anda</p>
                            </div>

                            <div class="animate-fadein-delay2 pt-1">
                                <button 
                                    type="submit" 
                                    class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]"
                                >
                                    <span class="material-symbols-outlined text-[18px]">send</span>
                                    Kirim Tautan Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-8 py-4 text-center">
                        <p class="text-sm text-gray-500">
                            Ingat kata sandi Anda?
                            <a href="{{ route('login') }}" class="ml-1 font-bold text-[#C6352F] hover:underline">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            function closeToast() {
                const toast = document.getElementById('successToast');
                if (toast) {
                    toast.classList.remove('toast-enter');
                    toast.classList.add('toast-exit');
                    setTimeout(() => {
                        toast.remove();
                    }, 400);
                }
            }

            // Auto close toast after 5 seconds
            if (document.getElementById('successToast')) {
                setTimeout(closeToast, 5000);
            }
        </script>
    @endpush
</x-guest-layout>
