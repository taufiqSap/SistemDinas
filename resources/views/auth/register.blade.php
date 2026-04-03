<x-guest-layout>
    @php
        $showStep2 = $errors->has('password') || $errors->has('password_confirmation');
    @endphp

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
            .textarea-field {
                width: 100%;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                background: rgba(249, 250, 251, 0.7);
                color: #111827;
                font-size: 0.875rem;
                transition: all 0.2s;
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                resize: none;
                font-family: inherit;
            }
            .textarea-field:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(198, 53, 47, 0.15);
                border-color: #c6352f;
            }
            .textarea-field::placeholder { color: #9ca3af; }
            @keyframes fadein {
                0% { opacity: 0; transform: translateY(16px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            @keyframes pulse-slow {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
            .animate-fadein { animation: fadein 0.5s ease both; }
            .animate-fadein-delay { animation: fadein 0.5s 0.1s ease both; }
            .animate-fadein-delay2 { animation: fadein 0.5s 0.2s ease both; }
            .animate-fadein-delay3 { animation: fadein 0.5s 0.3s ease both; }
            .animate-fadein-delay4 { animation: fadein 0.5s 0.4s ease both; }
            .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }

            .warning-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.45);
                z-index: 70;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .warning-backdrop.show { display: flex; }

            .warning-modal {
                width: 100%;
                max-width: 420px;
                border-radius: 16px;
                background: #ffffff;
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
                overflow: hidden;
            }
        </style>
    @endpush

    <div class="bg-[#F5F5F0] text-gray-900 antialiased">
        <main class="relative flex min-h-[calc(100vh-80px)]">
            <div class="absolute inset-0 z-0">
                <img alt="Landmark Kota Blitar" class="h-full w-full object-cover" src="{{ asset('images/bg.png') }}" />
                <div class="absolute inset-0 bg-gradient-to-br from-[#C6352F]/65 via-black/50 to-[#0F172A]/80"></div>
                <div class="animate-pulse-slow pointer-events-none absolute -bottom-16 -left-16 h-72 w-72 rounded-full bg-[#FFD700]/10 blur-3xl"></div>
                <div class="pointer-events-none absolute right-10 top-10 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>
            </div>

            <div class="relative z-10 flex flex-1 items-center justify-center p-4 py-12">
                <div class="w-full max-w-lg animate-fadein">
                    <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <div class="bg-gradient-to-r from-[#C6352F] to-[#a82b25] px-8 pb-6 pt-8 text-center">
                            <h1 class="text-2xl font-extrabold tracking-tight text-white">Buat Akun Baru</h1>
                            <p class="mt-1 text-sm text-white/75">Daftarkan diri Anda untuk mulai menyewa aset</p>
                        </div>

                        <div class="border-b border-gray-100 px-8">
                            <div class="cursor-default border-b-[3px] border-[#C6352F] py-3 text-center text-sm font-bold tracking-wide text-[#C6352F]">
                                Daftar
                            </div>
                        </div>

                        <div class="animate-fadein-delay px-8 pb-1 pt-5">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1.5">
                                    <div id="step1-dot" class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all {{ $showStep2 ? 'bg-[#C6352F] text-white' : 'bg-[#C6352F] text-white' }}">1</div>
                                    <span id="step1-label" class="text-xs font-semibold text-[#C6352F]">Data Diri</span>
                                </div>
                                <div class="h-0.5 flex-1 rounded-full bg-gray-200">
                                    <div id="step-progress" class="h-full rounded-full bg-[#C6352F] transition-all duration-500" style="width: {{ $showStep2 ? '100%' : '0%' }}"></div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div id="step2-dot" class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all {{ $showStep2 ? 'bg-[#C6352F] text-white' : 'bg-gray-200 text-gray-400' }}">2</div>
                                    <span id="step2-label" class="text-xs font-semibold {{ $showStep2 ? 'text-[#C6352F]' : 'text-gray-400' }}">Keamanan</span>
                                </div>
                            </div>
                        </div>

                        <form id="register-form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div id="step-1" class="{{ $showStep2 ? 'hidden' : '' }} flex flex-col gap-4 px-8 py-6">
                                <div class="animate-fadein-delay flex flex-col gap-1.5">
                                    <label for="nama" class="text-sm font-semibold text-gray-800">
                                        Nama Lengkap <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">badge</span>
                                        <input id="nama" name="name" class="input-field" placeholder="Masukkan nama lengkap Anda" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" />
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm" />
                                </div>

                                <div class="animate-fadein-delay2 flex flex-col gap-1.5">
                                    <label for="email" class="text-sm font-semibold text-gray-800">
                                        Email <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">mail</span>
                                        <input id="email" name="email" class="input-field" placeholder="contoh@email.com" type="email" value="{{ old('email') }}" required autocomplete="username" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm" />
                                </div>

                                <div class="animate-fadein-delay3 flex flex-col gap-1.5">
                                    <label for="nohp" class="text-sm font-semibold text-gray-800">
                                        Nomor HP <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">smartphone</span>
                                        <input id="nohp" name="nohp" class="input-field" placeholder="08xxxxxxxxxx" type="tel" value="{{ old('nohp') }}" oninput="this.value=this.value.replace(/[^0-9+]/g,'')" required />
                                    </div>
                                    <p class="pl-1 text-xs text-gray-400">Format: 08xx atau +628xx</p>
                                </div>

                                <div class="animate-fadein-delay4 flex flex-col gap-1.5">
                                    <label for="alamat" class="text-sm font-semibold text-gray-800">
                                        Alamat Lengkap <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-3 text-[20px] text-gray-400">home_pin</span>
                                        <textarea id="alamat" name="alamat" class="textarea-field" placeholder="Masukkan alamat lengkap Anda" rows="3" required>{{ old('alamat') }}</textarea>
                                    </div>
                                </div>

                                <div class="animate-fadein-delay4 pt-2">
                                    <button type="button" onclick="goToStep2()" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]">
                                        Lanjut
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </button>
                                </div>
                            </div>

                            <div id="step-2" class="{{ $showStep2 ? 'flex' : 'hidden' }} flex-col gap-4 px-8 py-6">
                                <div class="animate-fadein-delay flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                    <span class="material-symbols-outlined shrink-0 text-2xl text-[#C6352F]">info</span>
                                    <p class="text-xs leading-relaxed text-gray-500">Buat kata sandi yang kuat dengan minimal 8 karakter, kombinasi huruf besar, angka, dan simbol.</p>
                                </div>

                                <div class="animate-fadein-delay2 flex flex-col gap-1.5">
                                    <label for="pass1" class="text-sm font-semibold text-gray-800">
                                        Kata Sandi <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">lock</span>
                                        <input id="pass1" name="password" class="input-field pe-11" placeholder="Buat kata sandi baru" type="password" required autocomplete="new-password" oninput="checkStrength()" />
                                        <button type="button" onclick="togglePass('pass1','eye1')" class="absolute right-0 flex h-full items-center px-3.5 text-gray-400 transition-colors hover:text-gray-700">
                                            <span id="eye1" class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm" />
                                    <div class="mt-1 flex gap-1" id="strength-bars">
                                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all" id="bar1"></div>
                                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all" id="bar2"></div>
                                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all" id="bar3"></div>
                                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all" id="bar4"></div>
                                    </div>
                                    <p id="strength-text" class="pl-1 text-xs text-gray-400"></p>
                                </div>

                                <div class="animate-fadein-delay3 flex flex-col gap-1.5">
                                    <label for="pass2" class="text-sm font-semibold text-gray-800">
                                        Konfirmasi Kata Sandi <span class="text-[#C6352F]">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[20px] text-gray-400">lock_reset</span>
                                        <input id="pass2" name="password_confirmation" class="input-field pe-11" placeholder="Ulangi kata sandi Anda" type="password" required autocomplete="new-password" oninput="checkMatch()" />
                                        <button type="button" onclick="togglePass('pass2','eye2')" class="absolute right-0 flex h-full items-center px-3.5 text-gray-400 transition-colors hover:text-gray-700">
                                            <span id="eye2" class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm" />
                                    <p id="match-text" class="hidden pl-1 text-xs"></p>
                                </div>

                                <div class="animate-fadein-delay4 flex items-start gap-2.5">
                                    <input id="terms" type="checkbox" class="mt-0.5 h-4 w-4 shrink-0 cursor-pointer rounded border-gray-300 accent-[#C6352F]" />
                                    <label for="terms" class="select-none text-sm leading-relaxed text-gray-600">
                                        Saya menyetujui
                                        <a href="#" class="font-semibold text-[#C6352F] hover:underline">Syarat &amp; Ketentuan</a>
                                        serta
                                        <a href="#" class="font-semibold text-[#C6352F] hover:underline">Kebijakan Privasi</a>
                                        yang berlaku.
                                    </label>
                                </div>

                                <div class="animate-fadein-delay4 flex gap-3 pt-2">
                                    <button type="button" onclick="goToStep1()" class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-gray-100 text-sm font-bold text-gray-700 transition-all hover:bg-gray-200 active:scale-[0.98]">
                                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                                        Kembali
                                    </button>
                                    <button type="submit" class="flex h-12 flex-grow items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]">
                                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                                        Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="border-t border-gray-100 bg-gray-50 px-8 py-4 text-center">
                            <p class="text-sm text-gray-500">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="ml-1 font-bold text-[#C6352F] hover:underline">Masuk disini</a>
                            </p>
                        </div>
                    </div>

                    <p class="mt-5 text-center text-xs text-white/60">© 2025 Dinas Kebudayaan &amp; Pariwisata · Pemerintah Kota Blitar</p>
                </div>
            </div>
        </main>
    </div>

    <div id="warning-popup" class="warning-backdrop" role="dialog" aria-modal="true" aria-labelledby="warning-title">
        <div class="warning-modal">
            <div class="bg-[#C6352F] px-5 py-4 text-white">
                <h3 id="warning-title" class="text-base font-bold">Peringatan</h3>
            </div>
            <div class="px-5 py-4">
                <p id="warning-message" class="text-sm text-gray-700">Harap lengkapi data yang dibutuhkan.</p>
            </div>
            <div class="flex justify-end border-t border-gray-100 px-5 py-3">
                <button
                    type="button"
                    onclick="closeWarningPopup()"
                    class="rounded-lg bg-[#FFD700] px-4 py-2 text-sm font-bold text-gray-900 transition hover:brightness-105"
                >
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showWarningPopup(message) {
                const popup = document.getElementById('warning-popup');
                const messageElement = document.getElementById('warning-message');
                messageElement.textContent = message;
                popup.classList.add('show');
            }

            function closeWarningPopup() {
                document.getElementById('warning-popup').classList.remove('show');
            }

            function goToStep2() {
                const nama = document.getElementById('nama').value.trim();
                const email = document.getElementById('email').value.trim();
                const nohp = document.getElementById('nohp').value.trim();
                const alamat = document.getElementById('alamat').value.trim();

                if (!nama || !email || !nohp || !alamat) {
                    showWarningPopup('Harap isi data diri terlebih dahulu sebelum lanjut ke tahap keamanan.');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showWarningPopup('Format email tidak valid. Silakan periksa kembali email Anda.');
                    return;
                }

                document.getElementById('step-1').classList.add('hidden');
                const step2 = document.getElementById('step-2');
                step2.classList.remove('hidden');
                step2.classList.add('flex');
                step2.classList.add('flex-col');

                document.getElementById('step2-dot').classList.remove('bg-gray-200', 'text-gray-400');
                document.getElementById('step2-dot').classList.add('bg-[#C6352F]', 'text-white');
                document.getElementById('step2-label').classList.remove('text-gray-400');
                document.getElementById('step2-label').classList.add('text-[#C6352F]');
                document.getElementById('step-progress').style.width = '100%';
            }

            function goToStep1() {
                const step2 = document.getElementById('step-2');
                step2.classList.add('hidden');
                step2.classList.remove('flex');
                document.getElementById('step-1').classList.remove('hidden');

                document.getElementById('step2-dot').classList.add('bg-gray-200', 'text-gray-400');
                document.getElementById('step2-dot').classList.remove('bg-[#C6352F]', 'text-white');
                document.getElementById('step2-label').classList.add('text-gray-400');
                document.getElementById('step2-label').classList.remove('text-[#C6352F]');
                document.getElementById('step-progress').style.width = '0%';
            }

            function togglePass(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.textContent = input.type === 'password' ? 'visibility' : 'visibility_off';
            }

            function checkStrength() {
                const val = document.getElementById('pass1').value;
                let score = 0;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                const colors = ['', '#EF4444', '#F97316', '#EAB308', '#22C55E'];
                const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
                const textColors = ['', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500'];

                for (let i = 1; i <= 4; i++) {
                    const bar = document.getElementById('bar' + i);
                    bar.style.backgroundColor = i <= score ? colors[score] : '#E5E7EB';
                }

                const txt = document.getElementById('strength-text');
                txt.textContent = val.length > 0 ? 'Kekuatan: ' + (labels[score] || 'Lemah') : '';
                txt.className = 'pl-1 text-xs ' + (textColors[score] || 'text-gray-400');

                checkMatch();
            }

            function checkMatch() {
                const p1 = document.getElementById('pass1').value;
                const p2 = document.getElementById('pass2').value;
                const txt = document.getElementById('match-text');
                if (p2.length === 0) {
                    txt.classList.add('hidden');
                    return;
                }

                txt.classList.remove('hidden');
                if (p1 === p2) {
                    txt.textContent = 'Kata sandi cocok';
                    txt.className = 'pl-1 text-xs text-green-600';
                } else {
                    txt.textContent = 'Kata sandi tidak cocok';
                    txt.className = 'pl-1 text-xs text-red-500';
                }
            }

            document.getElementById('register-form').addEventListener('submit', function (event) {
                const p1 = document.getElementById('pass1').value;
                const p2 = document.getElementById('pass2').value;
                const terms = document.getElementById('terms').checked;

                if (!p1 || !p2) {
                    event.preventDefault();
                    showWarningPopup('Harap isi kata sandi terlebih dahulu.');
                    return;
                }

                if (p1 !== p2) {
                    event.preventDefault();
                    showWarningPopup('Kata sandi tidak cocok.');
                    return;
                }

                if (p1.length < 8) {
                    event.preventDefault();
                    showWarningPopup('Kata sandi minimal 8 karakter.');
                    return;
                }

                if (!terms) {
                    event.preventDefault();
                    showWarningPopup('Harap setujui syarat dan ketentuan terlebih dahulu.');
                }
            });

            document.getElementById('warning-popup').addEventListener('click', function (event) {
                if (event.target.id === 'warning-popup') {
                    closeWarningPopup();
                }
            });
        </script>
    @endpush
</x-guest-layout>
