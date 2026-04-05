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
                    href="{{ route('fasilitas.index') }}"
                    class="rounded-lg border border-white/30 px-4 py-2 font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Daftar Fasilitas
                </a>
                <a
                    href="{{ url('/#jadwal') }}"
                    class="rounded-lg bg-yellow-300 px-4 py-2 font-semibold text-[#7a1d1d] transition hover:bg-yellow-200"
                >
                    Cek Jadwal
                </a>
                <x-dropdown align="right" width="56" contentClasses="py-1 bg-white">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-left text-white transition hover:bg-white/15">
                            <span class="font-semibold">{{ auth()->user()->nama }}</span>
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->nama }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('booking.history')">
                            Riwayat Booking
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            Profil Saya
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a
                    href="{{ route('login') }}"
                    class="rounded-lg border border-white/30 px-4 py-2 font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Login
                </a>
                <a
                    href="{{ route('register') }}"
                    class="rounded-lg bg-yellow-300 px-4 py-2 font-semibold text-[#7a1d1d] transition hover:bg-yellow-200"
                >
                    Daftar
                </a>
            @endauth
        </nav>
    </div>
</header>