@props(['title' => config('app.name', 'Laravel')])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="bg-slate-950 font-sans text-slate-100 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.18),_transparent_34%),radial-gradient(circle_at_right,_rgba(251,191,36,0.14),_transparent_28%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]">
            <header class="border-b border-white/10 bg-slate-950/75 backdrop-blur-xl">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/15 text-sm font-black tracking-wide text-cyan-200 shadow-lg shadow-cyan-950/40">
                            DP
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200/80">Admin Console</p>
                            <h1 class="text-lg font-bold text-white sm:text-xl">Dinas Kebudayaan dan Pariwisata</h1>
                            <p class="text-sm text-slate-300">Panel pengelolaan data untuk admin testing dan operasional.</p>
                        </div>
                    </div>

                    @auth
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pengguna aktif</p>
                                <p class="text-sm font-semibold text-white">{{ auth()->user()->nama }}</p>
                                <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-semibold text-emerald-200">
                                Role: {{ ucfirst(auth()->user()->role) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:flex-row lg:px-8">
                <aside class="w-full lg:w-72 lg:shrink-0">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 shadow-2xl shadow-slate-950/40 backdrop-blur-xl">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Navigasi</p>
                            <p class="mt-1 text-lg font-semibold text-white">Menu admin</p>
                        </div>

                        <nav class="mt-5 space-y-2 text-sm font-medium">
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Dashboard</span>
                                <span class="text-xs {{ request()->routeIs('dashboard') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.bookings.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Booking </span>
                                <span class="text-xs {{ request()->routeIs('admin.bookings.*') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <a href="{{ route('admin.fasilitas.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.fasilitas.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Fasilitas</span>
                                <span class="text-xs {{ request()->routeIs('admin.fasilitas.*') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <a href="{{ route('admin.kegiatan.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.kegiatan.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Kegiatan</span>
                                <span class="text-xs {{ request()->routeIs('admin.kegiatan.*') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <a href="{{ route('admin.tipe-sewa.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.tipe-sewa.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Tipe Sewa</span>
                                <span class="text-xs {{ request()->routeIs('admin.tipe-sewa.*') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs('profile.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-950/30' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                                <span>Profil</span>
                                <span class="text-xs {{ request()->routeIs('profile.*') ? 'text-slate-700' : 'text-slate-400' }}"></span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-between rounded-2xl bg-rose-500/10 px-4 py-3 text-left text-rose-200 transition hover:bg-rose-500/20">
                                    <span>Keluar</span>
                                    <span class="text-xs text-rose-300">Logout</span>
                                </button>
                            </form>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>