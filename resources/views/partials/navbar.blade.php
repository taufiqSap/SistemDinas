<header class="sticky top-0 z-40 border-b border-red-900/40 bg-[#c62828] shadow-lg">
    <div class="flex w-full items-center justify-between px-3 py-3 sm:px-4 lg:px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/Icon.jpeg') }}"
                alt="Logo Kota Blitar"
                class="h-11 w-11 rounded-full bg-white/90 object-contain p-1 shadow-sm"
            />
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-yellow-100">SELARAS</p>
                <h1 class="text-lg font-bold text-white">DISBUDPAR</h1>
            </div>
        </a>

        <!-- Tombol hamburger untuk mobile -->
        <button id="navbar-toggle" type="button" class="inline-flex items-center rounded-lg border border-white/30 p-2 text-white hover:bg-white/10 lg:hidden" aria-controls="navbar-menu" aria-expanded="false">
            <span class="sr-only">Buka menu</span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Menu desktop (hidden di mobile) -->
        <nav id="navbar-menu" class="hidden w-full flex-col items-center gap-2 text-sm sm:gap-4 lg:flex lg:w-auto lg:flex-row">
            @auth
                <a
                    href="{{ route('fasilitas.index') }}"
                    class="w-full rounded-lg border border-white/30 px-4 py-2 text-center font-semibold text-white transition hover:bg-white hover:text-[#c62828] lg:w-auto"
                >
                    Daftar Fasilitas
                </a>
                <a
                    href="{{ url('/#jadwal') }}"
                    class="w-full rounded-lg bg-yellow-300 px-4 py-2 text-center font-semibold text-[#7a1d1d] transition hover:bg-yellow-200 lg:w-auto"
                >
                    Cek Jadwal
                </a>
                <x-dropdown align="right" width="56" contentClasses="py-1 bg-white">
                    <x-slot name="trigger">
                        <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-left text-white transition hover:bg-white/15 lg:w-auto">
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
                    class="w-full rounded-lg border border-white/30 px-4 py-2 text-center font-semibold text-white transition hover:bg-white hover:text-[#c62828] lg:w-auto"
                >
                    Login
                </a>
                <a
                    href="{{ route('register') }}"
                    class="w-full rounded-lg bg-yellow-300 px-4 py-2 text-center font-semibold text-[#7a1d1d] transition hover:bg-yellow-200 lg:w-auto"
                >
                    Daftar
                </a>
            @endauth
        </nav>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div id="mobile-menu" class="hidden border-t border-red-900/40 bg-[#b71c1c] px-4 py-3 lg:hidden">
        <div class="flex flex-col gap-2">
            @auth
                <a
                    href="{{ route('fasilitas.index') }}"
                    class="block w-full rounded-lg border border-white/30 px-4 py-2 text-center font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Daftar Fasilitas
                </a>
                <a
                    href="{{ url('/#jadwal') }}"
                    class="block w-full rounded-lg bg-yellow-300 px-4 py-2 text-center font-semibold text-[#7a1d1d] transition hover:bg-yellow-200"
                >
                    Cek Jadwal
                </a>

                <!-- Dropdown profil di mobile pakai <details> -->
                <details class="w-full">
                    <summary class="flex w-full cursor-pointer items-center justify-between rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-white hover:bg-white/15">
                        <span class="font-semibold">{{ auth()->user()->nama }}</span>
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <div class="mt-2 rounded-lg bg-white p-3 text-sm shadow-lg">
                        <p class="font-semibold text-gray-900">{{ auth()->user()->nama }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        <hr class="my-2 border-gray-200">
                        <a href="{{ route('booking.history') }}" class="block rounded px-3 py-2 hover:bg-gray-100">Riwayat Booking</a>
                        <a href="{{ route('profile.edit') }}" class="block rounded px-3 py-2 hover:bg-gray-100">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded px-3 py-2 text-left hover:bg-gray-100">Keluar</button>
                        </form>
                    </div>
                </details>
            @else
                <a
                    href="{{ route('login') }}"
                    class="block w-full rounded-lg border border-white/30 px-4 py-2 text-center font-semibold text-white transition hover:bg-white hover:text-[#c62828]"
                >
                    Login
                </a>
                <a
                    href="{{ route('register') }}"
                    class="block w-full rounded-lg bg-yellow-300 px-4 py-2 text-center font-semibold text-[#7a1d1d] transition hover:bg-yellow-200"
                >
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    (function() {
        const toggleBtn = document.getElementById('navbar-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('hidden');
            });
        }
    })();
</script>