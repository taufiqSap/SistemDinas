<x-admin-layout title="Booking User">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Handle Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Booking User</h2>
                    <p class="mt-1 text-sm text-slate-400">Pantau dan ubah status booking dari user.</p>
                </div>

                <form method="GET" class="flex items-center gap-2">
                    <select name="status" class="rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white">
                        <option value="">Semua Status</option>
                        @foreach ($statusOptions as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-full bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950">Filter</button>
                </form>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                        <thead class="bg-white/5 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Kode</th>
                                <th class="px-4 py-4 font-semibold">User</th>
                                <th class="px-4 py-4 font-semibold">Fasilitas</th>
                                <th class="px-4 py-4 font-semibold">Jadwal</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 bg-slate-950/40">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-4 font-semibold text-white">{{ $booking->kode_booking }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $booking->user?->nama ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ ucfirst($booking->status_booking) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-2 text-xs font-semibold text-cyan-100">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">Belum ada booking.</td>
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