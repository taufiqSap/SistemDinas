<x-admin-layout title="Detail Booking">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Handle Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">{{ $booking->kode_booking }}</h2>
                    <p class="mt-1 text-sm text-slate-700">Detail booking user dan status verifikasi.</p>
                </div>

                <a href="{{ route('admin.bookings.index') }}" class="rounded-full border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-red-50">Kembali</a>
            </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-4 rounded-3xl border border-red-100 bg-white p-5 shadow-sm">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">User</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->user?->nama ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->user?->email ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Fasilitas</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Tipe Sewa</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->tipeSewa?->nama_tipe ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kegiatan</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Durasi</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->durasi_hari }} hari</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Mulai</p><p class="mt-1 font-semibold text-slate-950">{{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Selesai</p><p class="mt-1 font-semibold text-slate-950">{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</p></div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Biaya Sewa</p><p class="mt-1 text-lg font-black text-emerald-600">Gratis</p></div>
                    </div>
                </div>

                <div class="space-y-4 rounded-3xl border border-red-100 bg-white p-5 shadow-sm">
                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status_booking" class="mb-1 block text-sm font-semibold text-slate-800">Status Booking</label>
                            <select id="status_booking" name="status_booking" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:ring-red-200">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status_booking', $booking->status_booking) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Simpan Status</button>
                    </form>

                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus booking ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-full border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">Hapus Booking</button>
                    </form>

                    <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-slate-700">
                        <p class="font-semibold text-slate-950">Status saat ini</p>
                        <p class="mt-1">{{ ucfirst($booking->status_booking) }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>