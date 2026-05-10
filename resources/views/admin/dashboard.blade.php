<x-admin-layout title="Dashboard Admin">
    @php
        $statusClasses = [
            'pending' => 'bg-amber-100 text-amber-800 ring-1 ring-inset ring-amber-200',
            'confirmed' => 'bg-emerald-100 text-emerald-800 ring-1 ring-inset ring-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-800 ring-1 ring-inset ring-rose-200',
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
        ];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl border border-red-100 bg-white shadow-lg shadow-red-100/60">
            <div class="grid gap-6 p-6 lg:grid-cols-[1.4fr_0.9fr] lg:p-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-red-500">Dashboard Admin</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Ringkasan operasional Dinas Pariwisata</h2>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-700 sm:text-base">
                        Pantau booking masuk, status verifikasi, data fasilitas, dan kegiatan aktif dari satu halaman.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-3xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
                        <p class="text-sm font-medium text-sky-700">Booking tercatat</p>
                        <p class="mt-2 text-4xl font-black text-slate-900">{{ number_format($stats[0]['value']) }}</p>
                        <p class="mt-2 text-sm text-slate-600">Data booking yang masuk ke sistem.</p>
                    </div>

                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                        <p class="text-sm font-medium text-amber-700">Menunggu verifikasi</p>
                        <p class="mt-2 text-4xl font-black text-slate-900">{{ number_format($stats[1]['value']) }}</p>
                        <p class="mt-2 text-sm text-slate-600">Booking yang masih pending.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                <article class="rounded-3xl border border-red-100 bg-white p-5 shadow-sm shadow-red-100/60">
                    <p class="text-sm font-medium text-slate-600">{{ $stat['label'] }}</p>
                    <div class="mt-4 flex items-end justify-between gap-4">
                        <span class="text-4xl font-black tracking-tight text-slate-900">{{ number_format($stat['value']) }}</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $stat['tone'] }}">{{ $stat['note'] }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60 lg:p-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Aktivitas terbaru</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-900">Booking terbaru</h3>
                </div>
                <p class="text-sm text-slate-600">Lima data booking terakhir yang tersimpan.</p>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-red-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-red-100 text-left text-sm">
                        <thead class="bg-red-50 text-xs uppercase tracking-[0.2em] text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Kode</th>
                                <th class="px-4 py-4 font-semibold">Kegiatan</th>
                                <th class="px-4 py-4 font-semibold">Fasilitas</th>
                                <th class="px-4 py-4 font-semibold">Jadwal</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white">
                            @forelse ($recentBookings as $booking)
                                <tr class="align-top transition hover:bg-red-50">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $booking->kode_booking }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-700">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$booking->status_booking] ?? 'bg-white/10 text-slate-200 ring-1 ring-inset ring-white/15' }}">
                                            {{ $statusLabels[$booking->status_booking] ?? ucfirst($booking->status_booking) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">
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
            <article class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Panduan cepat</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-900">Langkah kerja admin</h3>
                <div class="mt-5 space-y-4 text-sm leading-6 text-slate-700">
                    <p>1. Periksa booking yang masih pending sebelum jadwal berjalan.</p>
                    <p>2. Cocokkan fasilitas dan kegiatan yang dipilih user dengan ketersediaan data master.</p>
                    <p>3. Tetapkan status booking sesuai keputusan verifikasi lapangan.</p>
                    <p>4. Gunakan menu profil untuk menyesuaikan akun admin bila diperlukan.</p>
                </div>
            </article>

            <article class="rounded-3xl border border-red-100 bg-red-50 p-6 shadow-sm shadow-red-100/60">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Status sistem</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-900">Sumber data aktif</h3>
                <dl class="mt-5 space-y-4 text-sm text-slate-700">
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-4 py-3 shadow-sm">
                        <dt>Total booking</dt>
                        <dd class="font-semibold text-slate-900">{{ number_format($stats[0]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-4 py-3 shadow-sm">
                        <dt>Fasilitas tersedia</dt>
                        <dd class="font-semibold text-slate-900">{{ number_format($stats[3]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-4 py-3 shadow-sm">
                        <dt>Kegiatan aktif</dt>
                        <dd class="font-semibold text-slate-900">{{ number_format($stats[4]['value']) }}</dd>
                    </div>
                </dl>
            </article>
        </section>
    </div>
</x-admin-layout>