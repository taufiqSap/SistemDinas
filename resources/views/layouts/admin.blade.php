@props(['title' => config('app.name', 'Laravel')])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>
          <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            :root {
                --sidebar-expanded: 18rem;
                --sidebar-collapsed: 5.25rem;
            }

            .admin-shell {
                min-height: 100vh;
                background: linear-gradient(180deg, #fff7f7 0%, #ffffff 42%);
            }

            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 40;
                width: var(--sidebar-expanded);
                height: 100vh;
                transition: width 260ms ease, transform 260ms ease;
            }

            .admin-content {
                min-height: 100vh;
                margin-left: var(--sidebar-expanded);
                transition: margin-left 260ms ease;
            }

            .sidebar-toggle-desktop {
                position: fixed;
                left: var(--sidebar-expanded);
                top: 50%;
                z-index: 45;
                transform: translateY(-50%);
                transition: left 260ms ease;
            }

            body.sidebar-collapsed .admin-sidebar {
                width: var(--sidebar-collapsed);
            }

            body.sidebar-collapsed .admin-content {
                margin-left: var(--sidebar-collapsed);
            }

            body.sidebar-collapsed .sidebar-toggle-desktop {
                left: var(--sidebar-collapsed);
            }

            body.sidebar-collapsed .sidebar-label,
            body.sidebar-collapsed .sidebar-brand-copy,
            body.sidebar-collapsed .sidebar-user,
            body.sidebar-collapsed .sidebar-logout-text {
                opacity: 0;
                width: 0;
                overflow: hidden;
                white-space: nowrap;
            }

            body.sidebar-collapsed .sidebar-nav-link {
                justify-content: center;
            }

            body.sidebar-collapsed .sidebar-brand-row {
                justify-content: center;
            }

            body.sidebar-collapsed .sidebar-brand-logo {
                margin-right: 0;
            }

            @media (max-width: 1023px) {
                .admin-sidebar {
                    transform: translateX(-100%);
                    width: min(86vw, 18rem);
                }

                .admin-content {
                    margin-left: 0;
                }

                .sidebar-toggle-desktop {
                    display: none;
                }

                body.sidebar-open .admin-sidebar {
                    transform: translateX(0);
                }
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="bg-white font-sans text-slate-900 antialiased">
        <div class="admin-shell">
            <aside class="admin-sidebar border-r border-red-800 bg-gradient-to-b from-red-700 via-red-700 to-red-800 text-white shadow-2xl shadow-red-950/30">
                <div class="flex h-full flex-col p-4">
                    <div class="sidebar-brand-row mb-5 flex items-center">
                        <div class="sidebar-brand-logo mr-3 flex h-12 w-12 items-center justify-center rounded-xl bg-white/95">
                            <img src="{{ asset('images/Icon.png') }}" alt="Logo Kota Blitar" class="h-9 w-9 object-contain">
                        </div>
                        <div class="sidebar-brand-copy transition-all duration-200">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-red-100">Selaras</p>
                            <p class="text-lg font-bold leading-tight text-white">Admin Panel</p>
                        </div>
                    </div>

                    <nav class="flex-1 space-y-2 text-sm font-medium">
                        <a href="{{ route('dashboard') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776L12 3l8.25 6.776V20.25a.75.75 0 01-.75.75h-5.25a.75.75 0 01-.75-.75V15a1.5 1.5 0 00-3 0v5.25a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V9.776z" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.bookings.index') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('admin.bookings.*') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3.75v3M15.75 3.75v3M4.5 9.75h15M5.25 6.75h13.5a.75.75 0 01.75.75v12a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-12a.75.75 0 01.75-.75z" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">Booking</span>
                        </a>

                        <a href="{{ route('admin.fasilitas.index') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 20.25h16.5M5.25 20.25V7.5a.75.75 0 01.75-.75h12a.75.75 0 01.75.75v12.75M9 10.5h6M9 13.5h6M9 16.5h6" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">Fasilitas</span>
                        </a>

                        <a href="{{ route('admin.kegiatan.index') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('admin.kegiatan.*') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75h-7.5m7.5 4.5h-7.5m7.5 4.5h-7.5M4.5 5.25A1.5 1.5 0 016 3.75h12a1.5 1.5 0 011.5 1.5v13.5a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V5.25z" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">Kegiatan</span>
                        </a>

                        <a href="{{ route('admin.kategori.index') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('admin.kategori.*') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">Kategori</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="sidebar-nav-link flex items-center rounded-xl px-3 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-white text-red-700' : 'text-red-50 hover:bg-white/15' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.49 7.49 0 0012 15.75a7.49 7.49 0 00-5.982 2.975M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="sidebar-label ml-3 transition-all duration-200">User</span>
                        </a>

                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t border-white/20 pt-3">
                        @csrf
                        <button type="submit" class="sidebar-nav-link flex w-full items-center rounded-xl bg-white/10 px-3 py-3 text-left text-white transition hover:bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            <span class="sidebar-label sidebar-logout-text ml-3 transition-all duration-200">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <button id="sidebar-desktop-toggle" type="button" class="sidebar-toggle-desktop hidden rounded-r-xl border border-red-700 bg-red-700 px-2 py-4 text-white shadow-lg hover:bg-red-800 lg:block" aria-label="Toggle sidebar">
                <svg id="sidebar-desktop-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

            <div class="admin-content">
                <header class="sticky top-0 z-20 border-b border-red-100 bg-white/95 backdrop-blur">
                    <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button id="sidebar-mobile-toggle" type="button" class="rounded-lg border border-red-200 bg-white p-2 text-red-700 shadow-sm lg:hidden" aria-label="Buka menu">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-red-500">Selaras Admin Panel</p>
                                <p class="text-sm font-semibold text-slate-800 sm:text-base">Dinas Kebudayaan dan Pariwisata Kota Blitar</p>
                            </div>
                        </div>

                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    <div class="min-h-[calc(100vh-9rem)] rounded-3xl border border-red-100 bg-white p-4 shadow-sm sm:p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            (() => {
                const body = document.body;
                const desktopToggle = document.getElementById('sidebar-desktop-toggle');
                const desktopIcon = document.getElementById('sidebar-desktop-icon');
                const mobileToggle = document.getElementById('sidebar-mobile-toggle');
                const overlay = document.getElementById('sidebar-overlay');
                const COLLAPSE_KEY = 'admin.sidebar.collapsed';

                const syncDesktopIcon = () => {
                    if (!desktopIcon) {
                        return;
                    }

                    if (body.classList.contains('sidebar-collapsed')) {
                        desktopIcon.style.transform = 'rotate(180deg)';
                    } else {
                        desktopIcon.style.transform = 'rotate(0deg)';
                    }
                };

                const setMobileState = (open) => {
                    body.classList.toggle('sidebar-open', open);
                    if (overlay) {
                        overlay.classList.toggle('hidden', !open);
                    }
                };

                if (localStorage.getItem(COLLAPSE_KEY) === '1') {
                    body.classList.add('sidebar-collapsed');
                }
                syncDesktopIcon();

                desktopToggle?.addEventListener('click', () => {
                    body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem(COLLAPSE_KEY, body.classList.contains('sidebar-collapsed') ? '1' : '0');
                    syncDesktopIcon();
                });

                mobileToggle?.addEventListener('click', () => {
                    const open = !body.classList.contains('sidebar-open');
                    setMobileState(open);
                });

                overlay?.addEventListener('click', () => {
                    setMobileState(false);
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        setMobileState(false);
                    }
                });
            })();
        </script>

        @stack('scripts')
    </body>
</html>