<x-admin-layout title="Booking User">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Handle pemesanan</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Daftar Pemesanan</h2>
                    <p class="mt-1 text-sm text-slate-600">Pantau dan ubah status pemesanan dari user.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <form method="GET" class="flex w-full sm:w-auto gap-2">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode, kegiatan, atau nama user" class="w-full min-w-0 rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-red-400 focus:ring-red-200 sm:w-56">
                        <div class="flex items-center gap-2">
                            <select name="status" class="hidden sm:block rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-800">
                                <option value="">Semua Status</option>
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button class="hidden sm:inline-flex rounded-full bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Filter</button>
                        </div>
                    </form>

                    {{-- TOMBOL TAMBAH BOOKING --}}
                    <a href="{{ route('admin.bookings.create') }}" 
                       class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 whitespace-nowrap">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Booking
                    </a>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-red-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-red-100 text-left text-sm">
                        <thead class="bg-red-50 text-xs uppercase tracking-[0.2em] text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Kode</th>
                                <th class="px-4 py-4 font-semibold">Nama Pemesan</th>
                                <th class="px-4 py-4 font-semibold">Fasilitas</th>
                                <th class="px-4 py-4 font-semibold">Kegiatan</th>
                                <th class="px-4 py-4 font-semibold">Jadwal (Mulai - Selesai)</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-red-50">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $booking->kode_booking }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $booking->user?->nama ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-700 max-w-[200px] truncate" title="{{ $booking->kegiatan ?? '-' }}">
                                        {{ $booking->kegiatan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-700 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-block rounded-full px-2 py-1 text-xs font-semibold
                                            @if($booking->status_booking == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status_booking == 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status_booking == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status_booking) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </section>
    </div>
</x-admin-layout>