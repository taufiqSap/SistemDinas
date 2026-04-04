<x-admin-layout title="Dashboard Admin">
    @php
        $statusClasses = [
            'pending' => 'bg-amber-400/15 text-amber-200 ring-1 ring-inset ring-amber-400/20',
            'confirmed' => 'bg-emerald-400/15 text-emerald-200 ring-1 ring-inset ring-emerald-400/20',
            'cancelled' => 'bg-rose-400/15 text-rose-200 ring-1 ring-inset ring-rose-400/20',
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
        ];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
            <div class="grid gap-6 p-6 lg:grid-cols-[1.4fr_0.9fr] lg:p-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200/80">Dashboard Admin</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Ringkasan operasional Dinas Pariwisata</h2>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                        Pantau booking masuk, status verifikasi, data fasilitas, kegiatan aktif, dan pembayaran yang sudah tercatat dari satu halaman.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('profile.edit') }}" class="rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                            Kelola Profil
                        </a>
                        <a href="{{ url('/') }}" class="rounded-full border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Lihat Beranda
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-3xl border border-cyan-400/20 bg-cyan-400/10 p-5">
                        <p class="text-sm text-cyan-100/80">Booking tercatat</p>
                        <p class="mt-2 text-4xl font-black text-white">{{ number_format($stats[0]['value']) }}</p>
                        <p class="mt-2 text-sm text-cyan-100/70">Data booking yang masuk ke sistem.</p>
                    </div>

                    <div class="rounded-3xl border border-amber-400/20 bg-amber-400/10 p-5">
                        <p class="text-sm text-amber-100/80">Menunggu verifikasi</p>
                        <p class="mt-2 text-4xl font-black text-white">{{ number_format($stats[1]['value']) }}</p>
                        <p class="mt-2 text-sm text-amber-100/70">Booking yang masih pending.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                <article class="rounded-3xl border border-white/10 bg-white/5 p-5 shadow-lg shadow-slate-950/20 backdrop-blur-xl">
                    <p class="text-sm font-medium text-slate-300">{{ $stat['label'] }}</p>
                    <div class="mt-4 flex items-end justify-between gap-4">
                        <span class="text-4xl font-black tracking-tight text-white">{{ number_format($stat['value']) }}</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $stat['tone'] }}">{{ $stat['note'] }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="rounded-3xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl lg:p-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Aktivitas terbaru</p>
                    <h3 class="mt-2 text-2xl font-bold text-white">Booking terbaru</h3>
                </div>
                <p class="text-sm text-slate-400">Lima data booking terakhir yang tersimpan.</p>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                        <thead class="bg-white/5 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Kode</th>
                                <th class="px-4 py-4 font-semibold">Kegiatan</th>
                                <th class="px-4 py-4 font-semibold">Fasilitas</th>
                                <th class="px-4 py-4 font-semibold">Jadwal</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 bg-slate-950/40">
                            @forelse ($recentBookings as $booking)
                                <tr class="align-top transition hover:bg-white/5">
                                    <td class="px-4 py-4 font-semibold text-white">{{ $booking->kode_booking }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-300">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$booking->status_booking] ?? 'bg-white/10 text-slate-200 ring-1 ring-inset ring-white/15' }}">
                                            {{ $statusLabels[$booking->status_booking] ?? ucfirst($booking->status_booking) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right font-semibold text-white">
                                        Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                                        Belum ada data booking yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-slate-950/20 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Panduan cepat</p>
                <h3 class="mt-2 text-2xl font-bold text-white">Langkah kerja admin</h3>
                <div class="mt-5 space-y-4 text-sm leading-6 text-slate-300">
                    <p>1. Periksa booking yang masih pending sebelum jadwal berjalan.</p>
                    <p>2. Cocokkan fasilitas dan kegiatan yang dipilih user dengan ketersediaan data master.</p>
                    <p>3. Validasi pembayaran setelah bukti pembayaran masuk.</p>
                    <p>4. Gunakan menu profil untuk menyesuaikan akun admin saat testing.</p>
                </div>
            </article>

            <article class="rounded-3xl border border-cyan-400/15 bg-cyan-400/10 p-6 shadow-lg shadow-cyan-950/20 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-100/80">Status sistem</p>
                <h3 class="mt-2 text-2xl font-bold text-white">Sumber data aktif</h3>
                <dl class="mt-5 space-y-4 text-sm text-cyan-50/90">
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-950/30 px-4 py-3">
                        <dt>Total booking</dt>
                        <dd class="font-semibold text-white">{{ number_format($stats[0]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-950/30 px-4 py-3">
                        <dt>Fasilitas tersedia</dt>
                        <dd class="font-semibold text-white">{{ number_format($stats[3]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-950/30 px-4 py-3">
                        <dt>Kegiatan aktif</dt>
                        <dd class="font-semibold text-white">{{ number_format($stats[4]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-950/30 px-4 py-3">
                        <dt>Pembayaran tercatat</dt>
                        <dd class="font-semibold text-white">{{ number_format($stats[5]['value']) }}</dd>
                    </div>
                </dl>
            </article>
        </section>
    </div>
</x-admin-layout>