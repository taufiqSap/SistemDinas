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
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Handle Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Booking</h2>
                    <p class="mt-1 text-sm text-slate-600">Pantau dan ubah status booking dari user.</p>
                </div>

                <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <div class="w-full">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama user" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-red-400 focus:ring-red-200">
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <select name="status" class="hidden sm:block rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-800">
                            <option value="">Semua Status</option>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>

                        <button class="hidden sm:inline-flex rounded-full bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Filter</button>
                    </div>
                </form>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-red-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-red-100 text-left text-sm">
                        <thead class="bg-red-50 text-xs uppercase tracking-[0.2em] text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Kode</th>
                                <th class="px-4 py-4 font-semibold">User</th>
                                <th class="px-4 py-4 font-semibold">Fasilitas</th>
                                <th class="px-4 py-4 font-semibold">Jadwal</th>
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
                                    <td class="px-4 py-4 text-slate-700">{{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ ucfirst($booking->status_booking) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">Belum ada booking.</td>
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