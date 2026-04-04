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
                    href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('fasilitas.index') }}"
                    class="rounded-lg border border-white/30 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Dashboard
                </a>
            @else
                <a
                    href="{{ url('/') }}"
                    class="rounded-lg border border-white/30 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Kembali
                </a>
            @endauth
        </nav>
    </div>
</header>