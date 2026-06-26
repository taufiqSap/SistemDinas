<x-admin-layout title="Detail Booking">
    <div class="space-y-6">
        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi error umum (opsional tapi membantu) --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <!-- ... (konten utama sama seperti semula, tidak diubah) ... -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Handle Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">{{ $booking->kode_booking }}</h2>
                    <p class="mt-1 text-sm text-slate-700">Detail booking user dan status verifikasi.</p>
                </div>

                <a href="{{ route('admin.bookings.index') }}" class="rounded-full border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-red-50">Kembali</a>
            </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                <!-- Kiri: Informasi Booking (tidak diubah) -->
                <div class="space-y-4 rounded-3xl border border-red-100 bg-white p-5 shadow-sm">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">User</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->user?->nama ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->user?->email ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Fasilitas</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->fasilitas?->nama_fasilitas ?? '-' }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kegiatan</p><p class="mt-1 font-semibold text-slate-950">{{ $booking->kegiatan ?? '-' }}</p></div>
                        
                        {{-- Dokumen --}}
                        <div class="md:col-span-2">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Dokumen</p>
                            @if ($booking->dokumen_pdf)
                                <a href="{{ asset('storage/' . $booking->dokumen_pdf) }}" target="_blank" 
                                   class="mt-1 inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            @else
                                <p class="mt-1 text-slate-400">Tidak ada dokumen</p>
                            @endif
                        </div>

                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Mulai</p><p class="mt-1 font-semibold text-slate-950">{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y H:i') }}</p></div>
                        <div><p class="text-xs uppercase tracking-[0.2em] text-slate-500">Selesai</p><p class="mt-1 font-semibold text-slate-950">{{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d M Y H:i') }}</p></div>
                    </div>
                </div>

                <!-- Kanan: Status & Aksi -->
                <div class="space-y-4 rounded-3xl border border-red-100 bg-white p-5 shadow-sm">
                    @if ($booking->status_booking === 'pending')
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="bukaModalKonfirmasi('{{ route('admin.bookings.update', $booking) }}')"
                                    class="flex-1 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Konfirmasi
                            </button>
                            <button type="button" onclick="bukaModalTolak('{{ route('admin.bookings.update', $booking) }}')"
                                    class="flex-1 rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                                Tolak
                            </button>
                        </div>
                    @elseif ($booking->status_booking === 'approved')
                        <div class="rounded-2xl bg-emerald-50 p-4 text-center text-sm text-emerald-700">
                            Booking ini telah disetujui.
                        </div>
                    @elseif ($booking->status_booking === 'rejected')
                        <div class="rounded-2xl bg-amber-50 p-4 text-center text-sm text-amber-700">
                            Booking ini telah ditolak.
                            @if ($booking->alasan_penolakan)
                                <div class="mt-2 text-left text-xs">
                                    <strong>Alasan:</strong> {{ $booking->alasan_penolakan }}
                                </div>
                            @endif
                        </div>
                    @else {{-- cancelled --}}
                        <div class="rounded-2xl bg-rose-50 p-4 text-center text-sm text-rose-700">
                            Booking ini telah dibatalkan.
                            @if ($booking->alasan_pembatalan)
                                <div class="mt-2 text-left text-xs">
                                    <strong>Alasan:</strong> {{ $booking->alasan_pembatalan }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <button type="button" onclick="bukaModalHapus('{{ route('admin.bookings.destroy', $booking) }}')"
                            class="w-full rounded-full border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                        Hapus Booking
                    </button>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                        <p class="font-semibold text-slate-950">Status saat ini</p>
                        <p class="mt-1">{{ ucfirst($booking->status_booking) }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ========== MODAL KONFIRMASI (dengan area error) ========== --}}
    <div id="modalKonfirmasi" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-bold text-slate-900">Konfirmasi Booking</h3>
                <p class="text-sm text-slate-600">Apakah Anda yakin ingin menyetujui booking ini?</p>
            </div>

            {{-- AREA ERROR DINAMIS --}}
            <div id="errorKonfirmasi" class="px-5 pt-2 hidden">
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-700">
                    <!-- isi akan diisi oleh JavaScript -->
                </div>
            </div>

            <form id="formKonfirmasi" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_booking" value="approved">
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalKonfirmasi" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== MODAL TOLAK ========== --}}
    <div id="modalTolak" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-bold text-slate-900">Alasan Penolakan</h3>
                <p class="text-sm text-slate-600">Silakan isi alasan mengapa booking ini ditolak.</p>
            </div>
            <form id="formTolak" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_booking" value="rejected">
                <div>
                    <label for="alasan_penolakan" class="mb-1.5 block text-sm font-bold text-slate-800">Alasan</label>
                    <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4" 
                              class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:ring-red-200" 
                              placeholder="Contoh: Dokumen tidak lengkap..." required minlength="5"></textarea>
                </div>
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalTolak" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== MODAL HAPUS ========== --}}
    <div id="modalHapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-bold text-slate-900">Hapus Booking</h3>
                <p class="text-sm text-slate-600">Apakah Anda yakin ingin menghapus booking ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <form id="formHapus" method="POST" class="p-5">
                @csrf
                @method('DELETE')
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalHapus" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // ============ FUNGSI BUKA MODAL ============
        function bukaModalKonfirmasi(actionUrl) {
            const modal = document.getElementById('modalKonfirmasi');
            const form = document.getElementById('formKonfirmasi');
            form.action = actionUrl;
            // Sembunyikan error jika ada
            document.getElementById('errorKonfirmasi').classList.add('hidden');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function bukaModalTolak(actionUrl) {
            const modal = document.getElementById('modalTolak');
            const form = document.getElementById('formTolak');
            const textarea = document.getElementById('alasan_penolakan');
            form.action = actionUrl;
            textarea.value = '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => textarea.focus(), 100);
        }

        function bukaModalHapus(actionUrl) {
            const modal = document.getElementById('modalHapus');
            const form = document.getElementById('formHapus');
            form.action = actionUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // ============ TOMBOL BATAL ============
        document.getElementById('btnBatalKonfirmasi').addEventListener('click', function() {
            const modal = document.getElementById('modalKonfirmasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('errorKonfirmasi').classList.add('hidden');
        });

        document.getElementById('btnBatalTolak').addEventListener('click', function() {
            const modal = document.getElementById('modalTolak');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        document.getElementById('btnBatalHapus').addEventListener('click', function() {
            const modal = document.getElementById('modalHapus');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        // ============ TUTUP MODAL KETIKA KLIK DI LUAR ============
        document.querySelectorAll('.fixed.inset-0.z-50').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    this.classList.remove('flex');
                    // Jika modal konfirmasi, sembunyikan error juga
                    if (this.id === 'modalKonfirmasi') {
                        document.getElementById('errorKonfirmasi').classList.add('hidden');
                    }
                }
            });
        });

        // ============ CEK ERROR SAAT LOAD ============
        document.addEventListener('DOMContentLoaded', function() {
            // Jika ada error status_booking, buka modal konfirmasi dan tampilkan error
            @if ($errors->has('status_booking'))
                const modal = document.getElementById('modalKonfirmasi');
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                const errorDiv = document.getElementById('errorKonfirmasi');
                errorDiv.classList.remove('hidden');
                errorDiv.querySelector('div').innerText = '{{ $errors->first('status_booking') }}';
            @endif

            // Jika ada flash error_modal (dari controller), buka modal konfirmasi
            @if (session('error_modal') === 'konfirmasi')
                const modal = document.getElementById('modalKonfirmasi');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Jika error sudah ditangani di atas, tidak perlu tambahan
            @endif
        });
    </script>
    @endpush
</x-admin-layout>