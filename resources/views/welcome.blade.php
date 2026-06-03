<x-app-layout>
    @push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-red: #c62828;
            --brand-deep: #8e1f1f;
            --brand-gold: #fbc02d;
            --brand-ink: #1b1f27;
            --brand-soft: #f5f7fb;
        }

        .welcome-page {
            font-family: 'Public Sans', sans-serif;
            background: radial-gradient(circle at 15% 10%, #fff6e8 0%, #ffffff 45%, #eef4ff 100%);
            color: var(--brand-ink);
        }

        .display-title {
            font-family: 'Playfair Display', serif;
        }

        .hero-overlay {
            background: linear-gradient(118deg, rgba(27, 31, 39, 0.78) 0%, rgba(198, 40, 40, 0.82) 58%, rgba(251, 192, 45, 0.65) 100%);
        }

        .chat-admin-float {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            z-index: 60;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff;
            box-shadow: 0 18px 36px rgba(18, 140, 126, 0.28);
            transition: transform 180ms ease, box-shadow 180ms ease, filter 180ms ease;
        }

        .chat-admin-float:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 44px rgba(18, 140, 126, 0.34);
            filter: brightness(1.02);
        }

        .chat-admin-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
        }

        @media (prefers-reduced-motion: no-preference) {
            .rise-up {
                animation: riseUp 700ms ease-out forwards;
                opacity: 0;
                transform: translateY(20px);
            }

            .rise-delay-1 {
                animation-delay: 120ms;
            }

            .rise-delay-2 {
                animation-delay: 220ms;
            }

            .rise-delay-3 {
                animation-delay: 320ms;
            }

            @keyframes riseUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
    @endpush

    <div class="welcome-page min-h-screen py-6">
        @php
            $adminWhatsAppNumber = '6285737644100';
            $adminChatUrl = 'https://wa.me/' . $adminWhatsAppNumber . '?text=' . rawurlencode('Halo Admin, saya ingin bertanya tentang layanan penyewaan fasilitas.');
        @endphp

        <section class="mx-auto mt-6 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.png') }}');"></div>
                <div class="hero-overlay absolute inset-0"></div>

                <div class="relative z-10 px-6 py-16 sm:px-10 sm:py-20 lg:px-14">
                    <p class="rise-up text-sm font-semibold uppercase tracking-[0.2em] text-yellow-200">SELARAS</p>
                    <h2 class="display-title rise-up rise-delay-1 mt-3 max-w-3xl text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Sistem Penyewaan Fasilitas Dinas Secara Cepat dan Transparan
                    </h2>
                </div>
            </div>
        </section>

        @if ($adminChatUrl)
            <a
                href="{{ $adminChatUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="chat-admin-float fixed bottom-5 right-5 px-4 py-3"
                aria-label="Chat admin via WhatsApp"
            >
                <span class="chat-admin-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="h-6 w-6 fill-current" aria-hidden="true">
                        <path d="M19.11 17.42c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.16-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.9-.8-1.5-1.79-1.67-2.09-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5-.17 0-.37-.02-.57-.02-.2 0-.52.07-.8.37-.27.3-1.02 1-1.02 2.44s1.05 2.84 1.2 3.04c.15.2 2.07 3.16 5 4.43.7.3 1.25.48 1.68.62.7.22 1.34.19 1.85.11.56-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.18-1.42-.07-.11-.27-.18-.57-.33zM16.01 5.33c-5.9 0-10.7 4.8-10.7 10.7 0 1.88.49 3.72 1.42 5.33L6 26.67l5.45-1.4c1.56.85 3.31 1.3 5.07 1.3h.01c5.9 0 10.7-4.8 10.7-10.7 0-2.86-1.11-5.55-3.13-7.57a10.63 10.63 0 0 0-7.59-2.97zm0 19.48h-.01a8.7 8.7 0 0 1-4.44-1.22l-.32-.19-3.23.83.86-3.14-.21-.33a8.69 8.69 0 0 1-1.34-4.63c0-4.79 3.89-8.68 8.68-8.68 2.32 0 4.5.9 6.14 2.54a8.62 8.62 0 0 1 2.54 6.14c0 4.79-3.89 8.68-8.67 8.68z" />
                    </svg>
                </span>
                <span class="pr-1 text-sm font-bold">Chat Admin</span>
            </a>
        @endif

        <section id="jadwal" class="mx-auto mb-16 mt-12 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            @php
                $monthInput = request('month');
                $startOfMonth = now()->startOfMonth();

                if (is_string($monthInput) && preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
                    try {
                        $startOfMonth = \Illuminate\Support\Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
                    } catch (\Throwable $e) {
                        $startOfMonth = now()->startOfMonth();
                    }
                }

                $endOfMonth = $startOfMonth->copy()->endOfMonth();
                $daysInMonth = $startOfMonth->daysInMonth;
                $prevMonth = $startOfMonth->copy()->subMonth()->format('Y-m');
                $nextMonth = $startOfMonth->copy()->addMonth()->format('Y-m');
                $monthLabel = $startOfMonth->translatedFormat('F Y');

                $bookingPeriods = \App\Models\Booking::query()
                    ->where('status_booking', '!=', 'cancelled')
                    ->whereDate('tanggal_sewa', '<=', $endOfMonth->toDateString())
                    ->whereDate('tanggal_selesai', '>=', $startOfMonth->toDateString())
                    ->get(['id', 'tanggal_sewa', 'tanggal_selesai']);

                $bookedDates = [];
                foreach ($bookingPeriods as $booking) {
                    $start = \Illuminate\Support\Carbon::parse($booking->tanggal_sewa)->startOfDay();
                    $end = \Illuminate\Support\Carbon::parse($booking->tanggal_selesai)->startOfDay();

                    if ($end->lt($start)) {
                        continue;
                    }

                    $periodEnd = $end->gt($endOfMonth) ? $endOfMonth : $end;
                    $periodStart = $start->lt($startOfMonth) ? $startOfMonth : $start;

                    for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
                        $key = $date->format('Y-m-d');
                        $bookedDates[$key] = ($bookedDates[$key] ?? 0) + 1;
                    }
                }

                $filledDays = count($bookedDates);
                $emptyDays = $daysInMonth - $filledDays;
            @endphp

            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[var(--brand-red)]">Kalender Ketersediaan</p>
                    <h3 class="mt-2 text-3xl font-extrabold text-[var(--brand-ink)]">Jadwal Bulan {{ $monthLabel }}</h3>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold">
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">Tersedia: {{ $emptyDays }} hari</span>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">Terisi: {{ $filledDays }} hari</span>
                </div>
            </div>

            <div class="mb-5 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                        <a href="{{ route('home', array_merge(request()->except('month'), ['month' => $prevMonth])) }}#jadwal" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-white hover:text-slate-900">
                            Bulan Sebelumnya
                        </a>
                        <span class="px-2 text-sm font-bold text-slate-500">{{ $monthLabel }}</span>
                        <a href="{{ route('home', array_merge(request()->except('month'), ['month' => $nextMonth])) }}#jadwal" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-white hover:text-slate-900">
                            Bulan Berikutnya
                        </a>
                    </div>

                    <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2">
                        @foreach (request()->except('month') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label for="month" class="text-sm font-semibold text-slate-600">Pilih Bulan</label>
                        <input id="month" name="month" type="month" value="{{ $startOfMonth->format('Y-m') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[var(--brand-red)] focus:outline-none focus:ring-2 focus:ring-red-100">
                        <button type="submit" class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Tampilkan
                        </button>
                    </form>
                </div>
            </div>

            <div class="mb-5 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <div class="inline-flex items-center gap-2 text-gray-700">
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                        <span>Jadwal Tersedia</span>
                    </div>
                    <div class="inline-flex items-center gap-2 text-gray-700">
                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                        <span>Jadwal terisi</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-7">
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = $startOfMonth->copy()->day($day);
                        $key = $date->format('Y-m-d');
                        $bookingCount = $bookedDates[$key] ?? 0;
                        $isFilled = $bookingCount > 0;
                    @endphp

                    <button
                        type="button"
                        data-jadwal-card
                        data-date="{{ $key }}"
                        class="w-full text-left rounded-xl border p-4 shadow-sm transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-200 {{ $isFilled ? 'border-amber-200 bg-amber-50/70' : 'border-emerald-200 bg-emerald-50/70' }}"
                    >
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $date->translatedFormat('D') }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-[var(--brand-ink)]">{{ $day }}</p>

                        @if ($isFilled)
                            <p class="mt-2 inline-flex rounded-full bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">
                                Terisi
                            </p>
                        @else
                            <p class="mt-2 inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-800">
                                Tersedia
                            </p>
                        @endif
                    </button>
                @endfor
            </div>

            <div id="jadwal-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true" aria-labelledby="jadwal-modal-title">
                <div class="w-full max-w-3xl rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-start justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[var(--brand-red)]">Detail Jadwal Reservasi</p>
                            <h4 id="jadwal-modal-title" class="mt-1 text-lg font-bold text-slate-900">Memuat...</h4>
                        </div>
                        <button type="button" id="jadwal-modal-close" class="rounded-md p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup popup">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="max-h-[70vh] overflow-auto px-5 py-4">
                        <p id="jadwal-modal-loading" class="text-sm font-medium text-slate-500">Memuat data booking...</p>
                        <p id="jadwal-modal-error" class="hidden rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700"></p>
                        <div id="jadwal-modal-empty" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                            Tidak ada booking pada tanggal ini.
                        </div>
                        <div id="jadwal-modal-list" class="hidden space-y-3"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        (() => {
            const modal = document.getElementById('jadwal-modal');
            const closeButton = document.getElementById('jadwal-modal-close');
            const title = document.getElementById('jadwal-modal-title');
            const loading = document.getElementById('jadwal-modal-loading');
            const error = document.getElementById('jadwal-modal-error');
            const empty = document.getElementById('jadwal-modal-empty');
            const list = document.getElementById('jadwal-modal-list');
            const cards = document.querySelectorAll('[data-jadwal-card]');

            const statusLabel = {
                pending: 'Pending',
                confirmed: 'Dikonfirmasi',
                cancelled: 'Dibatalkan'
            };

            const statusClass = {
                pending: 'bg-amber-100 text-amber-800',
                confirmed: 'bg-emerald-100 text-emerald-800',
                cancelled: 'bg-red-100 text-red-800'
            };

            const resetModal = () => {
                loading.classList.remove('hidden');
                error.classList.add('hidden');
                empty.classList.add('hidden');
                list.classList.add('hidden');
                list.innerHTML = '';
            };

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            const bookingShowBaseUrl = @json(route('booking.show', ['date' => '__DATE__']));

            const buildItemHtml = (item) => {
                const status = item.status_booking || 'pending';
                const badgeClass = statusClass[status] || 'bg-slate-100 text-slate-700';
                const badgeText = statusLabel[status] || status;

                return '<article class="rounded-xl border border-slate-200 bg-slate-50 p-4">'
                    + '<div class="mb-2 flex items-center justify-between gap-2">'
                    + '<p class="text-sm font-bold text-slate-800">' + item.kode_booking + '</p>'
                    + '<span class="rounded-full px-2 py-1 text-xs font-semibold ' + badgeClass + '">' + badgeText + '</span>'
                    + '</div>'
                    + '<div class="grid gap-2 text-sm text-slate-700 sm:grid-cols-2">'
                    + '<p><span class="font-semibold text-slate-900">Pemesan:</span> ' + item.nama_pemesan + '</p>'
                    + '<p><span class="font-semibold text-slate-900">Agenda:</span> ' + item.agenda + '</p>'
                    + '<p><span class="font-semibold text-slate-900">Fasilitas:</span> ' + item.fasilitas + '</p>'
                    + '<p><span class="font-semibold text-slate-900">Mulai:</span> ' + item.tanggal_sewa + '</p>'
                    + '<p><span class="font-semibold text-slate-900">Selesai:</span> ' + item.tanggal_selesai + ' (' + item.durasi_hari + ' hari)</p>'
                    + '</div>'
                    + '</article>';
            };

            cards.forEach((card) => {
                card.addEventListener('click', async () => {
                    const date = card.getAttribute('data-date');

                    if (!date) {
                        return;
                    }

                    openModal();
                    resetModal();

                    try {
                        const response = await fetch(bookingShowBaseUrl.replace('__DATE__', date), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw new Error(payload.message || 'Gagal mengambil detail booking.');
                        }

                        title.textContent = 'Jadwal ' + payload.tanggal_label;
                        loading.classList.add('hidden');

                        if (!payload.bookings || payload.bookings.length === 0) {
                            empty.classList.remove('hidden');
                            return;
                        }

                        // Deduplicate bookings by pemesan (show only one per pemesan)
                        const bookings = payload.bookings || [];
                        const seen = [];
                        const uniqueBookings = [];

                        for (const b of bookings) {
                            const key = b.nama_pemesan || b.user_id || b.kode_booking || JSON.stringify(b);
                            if (!seen.includes(key)) {
                                seen.push(key);
                                uniqueBookings.push(b);
                            }
                        }

                        list.innerHTML = uniqueBookings.map(buildItemHtml).join('');
                        list.classList.remove('hidden');
                    } catch (exception) {
                        loading.classList.add('hidden');
                        error.textContent = exception.message || 'Terjadi kesalahan saat memuat data.';
                        error.classList.remove('hidden');
                        title.textContent = 'Detail Jadwal Reservasi';
                    }
                });
            });

            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>
