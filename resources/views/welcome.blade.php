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
        <section class="mx-auto mt-6 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.png') }}');"></div>
                <div class="hero-overlay absolute inset-0"></div>

                <div class="relative z-10 px-6 py-16 sm:px-10 sm:py-20 lg:px-14">
                    <p class="rise-up text-sm font-semibold uppercase tracking-[0.2em] text-yellow-200">Sistem Layanan Aset</p>
                    <h2 class="display-title rise-up rise-delay-1 mt-3 max-w-3xl text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Penyewaan Fasilitas Dinas Secara Transparan, Cepat, dan Akuntabel
                    </h2>
                    <p class="rise-up rise-delay-2 mt-5 max-w-2xl text-base text-white/90 sm:text-lg">
                        Jelajahi fasilitas pemerintah Kota Blitar untuk kegiatan publik, komunitas, dan kebutuhan resmi lainnya.
                    </p>

                </div>
            </div>
        </section>

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

                    <article class="rounded-xl border p-4 shadow-sm transition hover:-translate-y-0.5 {{ $isFilled ? 'border-amber-200 bg-amber-50/70' : 'border-emerald-200 bg-emerald-50/70' }}">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $date->translatedFormat('D') }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-[var(--brand-ink)]">{{ $day }}</p>

                        @if ($isFilled)
                            <p class="mt-2 inline-flex rounded-full bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">
                                Terisi ({{ $bookingCount }})
                            </p>
                        @else
                            <p class="mt-2 inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-800">
                                Tersedia
                            </p>
                        @endif
                    </article>
                @endfor
            </div>
        </section>
    </div>
</x-app-layout>
