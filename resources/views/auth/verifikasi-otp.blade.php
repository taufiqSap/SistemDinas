{{-- resources/views/auth/verifikasi-otp.blade.php --}}
<x-guest-layout>
    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .otp-input {
                width: 3rem;
                height: 3.5rem;
                text-align: center;
                font-size: 1.25rem;
                font-weight: 600;
                border: 2px solid #e5e7eb;
                border-radius: 0.75rem;
                background: #f9fafb;
                transition: all 0.2s;
                color: #111827;
            }
            .otp-input:focus {
                outline: none;
                border-color: #C6352F;
                box-shadow: 0 0 0 4px rgba(198,53,47,0.1);
                background: white;
            }
            .otp-input:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            .timer-text {
                font-feature-settings: "tnum";
            }

            @media (min-width: 640px) {
                .otp-input {
                    width: 3.5rem;
                    height: 4rem;
                    font-size: 1.5rem;
                }
            }
        </style>
    @endpush

    <div class="flex-1 flex items-center justify-center p-4 md:p-6 bg-[#F5F5F0]">
        <div class="w-full max-w-md animate-fadein">
            <div class="overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="bg-gradient-to-r from-[#C6352F] to-[#a82b25] px-6 py-6 md:px-8 md:pb-6 md:pt-8 text-center">
                    <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-white">Verifikasi OTP</h1>
                    <p class="mt-1 text-xs md:text-sm text-white/75">Masukkan kode yang dikirim ke WhatsApp Anda</p>
                </div>

                <div class="p-6 md:p-8">
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700 border border-green-200">
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

                    <div class="mb-4 flex flex-wrap items-center justify-between rounded-lg bg-gray-50 px-4 py-2 text-sm">
                        <span class="text-gray-600">Nomor HP</span>
                        <span class="font-semibold text-gray-800 break-all">{{ $no_hp }}</span>
                    </div>

                    <form method="POST" action="{{ route('verifikasi.otp.verify') }}" id="otp-form">
                        @csrf
                        <input type="hidden" name="no_hp" value="{{ $no_hp }}">

                        <div class="mb-6 flex justify-center gap-2 sm:gap-3">
                            @for ($i = 1; $i <= 6; $i++)
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" 
                                       class="otp-input" id="otp-{{ $i }}" name="kode[]" 
                                       autocomplete="off" required>
                            @endfor
                        </div>

                        <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#FFD700] text-sm font-bold uppercase tracking-wide text-gray-900 shadow transition-all hover:brightness-105 active:scale-[0.98]">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            Verifikasi OTP
                        </button>
                    </form>

                    <div class="mt-6 flex items-center justify-between text-sm">
                        <span class="text-gray-500">Waktu tersisa:</span>
                        <span id="timer" class="font-mono font-semibold text-[#C6352F] timer-text" data-expired="{{ $expired_at ? $expired_at->timestamp : 0 }}">
                            @if ($expired_at)
                                {{ $expired_at->diffInSeconds(now()) > 0 ? gmdate('i:s', $expired_at->diffInSeconds(now())) : '00:00' }}
                            @else
                                00:00
                            @endif
                        </span>
                    </div>

                    <div class="mt-4 text-center">
                        <form method="POST" action="{{ route('verifikasi.otp.resend') }}" id="resend-form">
                            @csrf
                            <input type="hidden" name="no_hp" value="{{ $no_hp }}">
                            <button type="submit" id="resend-btn" class="text-sm font-semibold text-[#C6352F] hover:underline disabled:text-gray-400 disabled:hover:no-underline" disabled>
                                Kirim Ulang OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto focus & auto-advance
        const inputs = document.querySelectorAll('.otp-input');
        if (inputs.length) inputs[0].focus();

        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const val = this.value.replace(/\D/g, '');
                this.value = val.slice(0, 1);
                if (val && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6);
                for (let i = 0; i < digits.length && i < inputs.length; i++) {
                    inputs[i].value = digits[i];
                }
                if (digits.length > 0) {
                    const nextIndex = Math.min(digits.length, inputs.length - 1);
                    inputs[nextIndex].focus();
                }
            });
        });

        // ========== TIMER ==========
        const timerElement = document.getElementById('timer');
        const expiredTimestamp = parseInt(timerElement.dataset.expired, 10);
        const resendBtn = document.getElementById('resend-btn');

        // Jika timestamp tidak valid (0 atau NaN), langsung set 00:00 dan enable button
        if (isNaN(expiredTimestamp) || expiredTimestamp <= 0) {
            timerElement.textContent = '00:00';
            if (resendBtn) resendBtn.disabled = false;
        } else {
            let seconds = Math.max(0, expiredTimestamp - Math.floor(Date.now() / 1000));
            function updateTimer() {
                if (seconds <= 0) {
                    timerElement.textContent = '00:00';
                    if (resendBtn) resendBtn.disabled = false;
                    clearInterval(timerInterval);
                    return;
                }
                seconds--;
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                timerElement.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            }
            let timerInterval = setInterval(updateTimer, 1000); // 1000 ms = 1 detik
            if (seconds <= 0) {
                clearInterval(timerInterval);
                if (resendBtn) resendBtn.disabled = false;
                timerElement.textContent = '00:00';
            }
        }
    });
    </script>
    @endpush
</x-guest-layout>