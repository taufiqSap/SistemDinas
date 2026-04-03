<header class="sticky top-0 z-40 border-b border-red-900/40 bg-[#c62828] shadow-lg">
    <div class="flex w-full items-center justify-between px-3 py-3 sm:px-4 lg:px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/Icon.png') }}"
                alt="Logo Kota Blitar"
                class="h-11 w-11 rounded-full bg-white/90 object-contain p-1 shadow-sm"
            />
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-yellow-100">Pemerintah Kota Blitar</p>
                <h1 class="text-lg font-bold text-white">Dinas Kebudayaan dan Pariwisata</h1>
            </div>
        </a>

        <nav class="flex items-center gap-2 text-sm sm:gap-4">
            @auth
                <a
                    href="{{ route('dashboard') }}"
                    class="rounded-lg border border-white/30 px-4 py-2 font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Dashboard
                </a>
            @else
                @if (\Illuminate\Support\Facades\Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg border border-white/30 px-4 py-2 font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                    >
                        Masuk
                    </a>
                @endif

                @if (\Illuminate\Support\Facades\Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="rounded-lg bg-[#fbc02d] px-4 py-2 font-bold text-[#1b1f27] transition hover:bg-yellow-300"
                    >
                        Daftar
                    </a>
                @endif
            @endauth
        </nav>
    </div>
</header>