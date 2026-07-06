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
                box-shadow: 0 0 0 2px rgba(37, 211, 102, 0.2);
                border-color: #25D366;
            }
            .input-field::placeholder { color: #9ca3af; }
            @keyframes fadein {
                0% { opacity: 0; transform: translateY(16px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .animate-fadein { animation: fadein 0.5s ease both; }
            .btn-send {
                background: #25D366;
                transition: all 0.2s;
            }
            .btn-send:hover {
                background: #128C7E;
                transform: scale(1.02);
            }
            .btn-send:active {
                transform: scale(0.98);
            }
            .link-login {
                color: #25D366;
                font-weight: 700;
            }
            .link-login:hover {
                text-decoration: underline;
                color: #128C7E;
            }
        </style>
    @endpush

    <div class="flex-1 flex items-center justify-center p-4 md:p-6 bg-[#F5F5F0]">
        <div class="w-full max-w-md animate-fadein">
            <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="bg-gradient-to-r from-[#128C7E] to-[#25D366] px-6 py-6 md:px-8 md:pb-6 md:pt-8 text-center">
                    <span class="material-symbols-outlined text-white text-4xl md:text-5xl mb-2">chat</span>
                    <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-white">Kirim OTP</h1>
                    <p class="mt-1 text-xs md:text-sm text-white/90">Masukkan nomor HP untuk menerima kode verifikasi</p>
                </div>

                <div class="p-6 md:p-8">
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700 border border-green-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 border border-red-200">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kirim.otp.send') }}">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label for="no_hp" class="text-sm font-semibold text-gray-800">
                                Nomor HP <span class="text-[#25D366]">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">smartphone</span>
                                <input id="no_hp" name="no_hp" class="input-field" placeholder="08xxxxxxxxxx" type="tel" value="{{ old('no_hp') }}" oninput="this.value=this.value.replace(/[^0-9+]/g,'')" required />
                            </div>
                            <p class="pl-1 text-xs text-gray-400">Format: 08xx atau +628xx</p>
                        </div>

                        <button type="submit" class="btn-send mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-xl text-sm font-bold uppercase tracking-wide text-white shadow-lg transition-all hover:shadow-xl active:scale-[0.98]">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim OTP
                        </button>
                    </form>

                    
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>