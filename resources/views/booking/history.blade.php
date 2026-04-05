<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">History Booking</h2>
                <p class="mt-1 text-sm text-gray-600">Daftar booking milik akun Anda sendiri. Halaman ini read-only.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Total Booking</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ $summary['total'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">Pending</p>
                    <p class="mt-2 text-3xl font-black text-amber-700">{{ $summary['pending'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Confirmed</p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">{{ $summary['confirmed'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Cancelled</p>
                    <p class="mt-2 text-3xl font-black text-rose-700">{{ $summary['cancelled'] ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 p-6">
                    <form method="GET" action="{{ route('booking.history') }}" class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="q" class="mb-1.5 block text-sm font-semibold text-gray-700">Cari booking</label>
                            <input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}" placeholder="Kode, fasilitas, tipe, atau kegiatan" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-semibold text-gray-700">Status booking</label>
                            <select id="status" name="status" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua status</option>
                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                                <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirmed</option>
                                <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-3">
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('booking.history') }}" class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    @if ($bookings->count() === 0)
                        <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                            <p class="text-lg font-semibold text-gray-800">Belum ada booking</p>
                            <p class="mt-2 text-sm text-gray-500">Riwayat booking Anda akan muncul di sini setelah melakukan pemesanan.</p>
                            <div class="mt-5">
                                <a href="{{ route('fasilitas.index') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                    Lihat Fasilitas
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Fasilitas</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tipe</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kegiatan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Total</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Booking</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($bookings as $booking)
                                        @php
                                            $bookingStatusClass = match ($booking->status_booking) {
                                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                                'cancelled' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-amber-100 text-amber-700',
                                            };

                                            $payment = $booking->latestPembayaran;
                                            $paymentStatus = $payment?->status_pembayaran ?? '-';
                                            $paymentStatusClass = match ($paymentStatus) {
                                                'verified' => 'bg-emerald-100 text-emerald-700',
                                                'pending' => 'bg-amber-100 text-amber-700',
                                                'rejected' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-4 font-medium text-gray-900">{{ $booking->kode_booking }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->tipeSewa?->nama_tipe ?? '-' }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->tanggal_sewa }} - {{ $booking->tanggal_selesai }}</td>
                                            <td class="px-4 py-4 text-gray-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $bookingStatusClass }}">{{ ucfirst($booking->status_booking) }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="space-y-1">
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClass }}">{{ ucfirst($paymentStatus) }}</span>
                                                    @if ($payment)
                                                        <p class="text-xs text-gray-500">{{ $payment->kode_pembayaran }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="grid gap-4 lg:hidden">
                            @foreach ($bookings as $booking)
                                @php
                                    $bookingStatusClass = match ($booking->status_booking) {
                                        'confirmed' => 'bg-emerald-100 text-emerald-700',
                                        'cancelled' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };

                                    $payment = $booking->latestPembayaran;
                                    $paymentStatus = $payment?->status_pembayaran ?? '-';
                                    $paymentStatusClass = match ($paymentStatus) {
                                        'verified' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'rejected' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <article class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Kode Booking</p>
                                            <h3 class="mt-1 text-base font-bold text-gray-900">{{ $booking->kode_booking }}</h3>
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $bookingStatusClass }}">{{ ucfirst($booking->status_booking) }}</span>
                                    </div>

                                    <div class="mt-4 grid gap-3 text-sm text-gray-700 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Fasilitas</p>
                                            <p class="mt-1 font-medium">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Tipe</p>
                                            <p class="mt-1 font-medium">{{ $booking->tipeSewa?->nama_tipe ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Kegiatan</p>
                                            <p class="mt-1 font-medium">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Tanggal</p>
                                            <p class="mt-1 font-medium">{{ $booking->tanggal_sewa }} - {{ $booking->tanggal_selesai }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Total</p>
                                            <p class="mt-1 font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Pembayaran</p>
                                            <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClass }}">{{ ucfirst($paymentStatus) }}</span>
                                            @if ($payment)
                                                <p class="mt-1 text-xs text-gray-500">{{ $payment->kode_pembayaran }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
