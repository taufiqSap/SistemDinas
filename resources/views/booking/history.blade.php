<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">History Booking</h2>
                <p class="mt-1 text-sm text-gray-600">Daftar booking anda.</p>
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

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc space-y-1 ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 grid gap-4 md:grid-cols-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Total Booking</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ $summary['total'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">Pending</p>
                    <p class="mt-2 text-3xl font-black text-amber-700">{{ $summary['pending'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Disetujui</p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">{{ $summary['approved'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-600">Selesai</p>
                    <p class="mt-2 text-3xl font-black text-sky-700">{{ $summary['completed'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Dibatalkan</p>
                    <p class="mt-2 text-3xl font-black text-rose-700">{{ $summary['cancelled'] ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 p-6">
                    <form method="GET" action="{{ route('booking.history') }}" class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-semibold text-gray-700">Status booking</label>
                            <select id="status" name="status" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua status</option>
                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                                <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
                                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
                                <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
                                <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="md:col-span-3 flex items-end gap-3">
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
                        <!-- Tabel Desktop -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Fasilitas</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kegiatan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Jadwal (Mulai - Selesai)</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Alasan Batal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($bookings as $booking)
                                        @php
                                            $bookingStatusClass = match ($booking->status_booking) {
                                                'approved' => 'bg-emerald-100 text-emerald-700',
                                                'completed' => 'bg-sky-100 text-sky-700',
                                                'rejected' => 'bg-rose-100 text-rose-700',
                                                'cancelled' => 'bg-gray-200 text-gray-700',
                                                default => 'bg-amber-100 text-amber-700',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-4 font-medium text-gray-900">{{ $booking->kode_booking }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</td>
                                            <td class="px-4 py-4 text-gray-700">{{ $booking->kegiatan ?? '-' }}</td>
                                            <td class="px-4 py-4 text-gray-700 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $bookingStatusClass }}">
                                                    {{ ucfirst($booking->status_booking) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-gray-700 max-w-[180px] truncate" title="{{ $booking->status_booking === 'cancelled' ? ($booking->alasan_pembatalan ?? '') : '' }}">
                                                @if ($booking->status_booking === 'cancelled')
                                                    {{ $booking->alasan_pembatalan ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @if (in_array($booking->status_booking, ['pending', 'approved']))
                                                    <button type="button" 
                                                            onclick="bukaModalBatal({{ $booking->id }}, '{{ $booking->kode_booking }}')"
                                                            class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-200">
                                                        Batalkan
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Tampilan Mobile -->
                        <div class="grid gap-4 lg:hidden">
                            @foreach ($bookings as $booking)
                                @php
                                    $bookingStatusClass = match ($booking->status_booking) {
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                                'completed' => 'bg-sky-100 text-sky-700',
                                        'rejected' => 'bg-rose-100 text-rose-700',
                                        'cancelled' => 'bg-gray-200 text-gray-700',
                                        default => 'bg-amber-100 text-amber-700',
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
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Kegiatan</p>
                                            <p class="mt-1 font-medium">{{ $booking->kegiatan ?? '-' }}</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Jadwal</p>
                                            <p class="mt-1 font-medium">
                                                {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d M Y H:i') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Durasi</p>
                                            <p class="mt-1 font-medium">
                                                @php
                                                    $mulai = \Carbon\Carbon::parse($booking->waktu_mulai);
                                                    $selesai = \Carbon\Carbon::parse($booking->waktu_selesai);
                                                    $diff = $mulai->diffInHours($selesai);
                                                @endphp
                                                {{ $diff }} jam
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Dibuat Pada</p>
                                            <p class="mt-1 font-medium">{{ $booking->created_at->format('d M Y H:i') }}</p>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Alasan Batal</p>
                                            <p class="mt-1 font-medium {{ $booking->status_booking === 'cancelled' ? 'text-rose-700' : 'text-gray-500' }}">
                                                {{ $booking->status_booking === 'cancelled' ? ($booking->alasan_pembatalan ?? '-') : '-' }}
                                            </p>
                                        </div>

                                        @if (in_array($booking->status_booking, ['pending', 'approved']))
                                            <div class="sm:col-span-2 mt-2">
                                                <button type="button" 
                                                        onclick="bukaModalBatal({{ $booking->id }}, '{{ $booking->kode_booking }}')"
                                                        class="w-full rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-200">
                                                    Batalkan Booking
                                                </button>
                                            </div>
                                        @endif
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

    {{-- ========== MODAL PEMBATALAN USER ========== --}}
    <div id="modalBatalUser" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-bold text-slate-900">Batalkan Booking</h3>
                <p class="text-sm text-slate-600">Silakan isi alasan pembatalan.</p>
            </div>
            <form id="formBatalUser" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="booking_id" id="booking_id_batal">
                <div>
                    <label for="alasan_pembatalan_user" class="mb-1.5 block text-sm font-bold text-slate-800">Alasan</label>
                    <textarea id="alasan_pembatalan_user" name="alasan_pembatalan" rows="4" 
                              class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:ring-red-200" 
                              placeholder="Contoh: Jadwal berubah..." required minlength="5"></textarea>
                </div>
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalModalUser" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Konfirmasi Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
<script>
    function bukaModalBatal(bookingId, kodeBooking) {
        const modal = document.getElementById('modalBatalUser');
        const form = document.getElementById('formBatalUser');
        const inputId = document.getElementById('booking_id_batal');
        const textarea = document.getElementById('alasan_pembatalan_user');

        // Buat URL dengan placeholder :id, lalu ganti dengan bookingId
        const url = "{{ route('booking.cancel', ['id' => ':id']) }}".replace(':id', bookingId);
        form.action = url;

        inputId.value = bookingId;
        textarea.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => textarea.focus(), 100);
    }

    document.getElementById('btnBatalModalUser').addEventListener('click', function() {
        const modal = document.getElementById('modalBatalUser');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    document.getElementById('modalBatalUser').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
</script>
@endpush
</x-app-layout>