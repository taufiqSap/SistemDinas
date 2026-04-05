<x-admin-layout title="Detail Booking">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Handle Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">{{ $booking->kode_booking }}</h2>
                    <p class="mt-1 text-sm text-slate-400">Detail booking user dan status verifikasi.</p>
                </div>

                <a href="{{ route('admin.bookings.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200">Kembali</a>
            </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">User</p><p class="mt-1 font-semibold text-white">{{ $booking->user?->nama ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Email</p><p class="mt-1 font-semibold text-white">{{ $booking->user?->email ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fasilitas</p><p class="mt-1 font-semibold text-white">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tipe Sewa</p><p class="mt-1 font-semibold text-white">{{ $booking->tipeSewa?->nama_tipe ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kegiatan</p><p class="mt-1 font-semibold text-white">{{ $booking->kegiatan?->nama_kegiatan ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Durasi</p><p class="mt-1 font-semibold text-white">{{ $booking->durasi_hari }} hari</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Mulai</p><p class="mt-1 font-semibold text-white">{{ \Carbon\Carbon::parse($booking->tanggal_sewa)->format('d M Y') }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Selesai</p><p class="mt-1 font-semibold text-white">{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</p></div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Harga</p><p class="mt-1 text-lg font-black text-cyan-200">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-400">Deadline Pembayaran</p><p class="mt-1 font-semibold text-white">{{ $booking->deadline_pembayaran ? \Carbon\Carbon::parse($booking->deadline_pembayaran)->format('d M Y H:i') : '-' }}</p></div>
                    </div>
                </div>

                <div class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status_booking" class="mb-1 block text-sm font-semibold text-slate-200">Status Booking</label>
                            <select id="status_booking" name="status_booking" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status_booking', $booking->status_booking) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950">Simpan Status</button>
                    </form>

                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus booking ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-full border border-rose-400/30 bg-rose-400/10 px-5 py-3 text-sm font-semibold text-rose-100">Hapus Booking</button>
                    </form>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                        <p class="font-semibold text-white">Status saat ini</p>
                        <p class="mt-1">{{ ucfirst($booking->status_booking) }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>