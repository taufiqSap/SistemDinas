<x-admin-layout title="Tambah Booking Baru">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Tambah Booking</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">Buat Booking Baru</h2>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="rounded-full border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-red-50">Kembali</a>
            </div>

            <form method="POST" action="{{ route('admin.bookings.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Waktu Mulai --}}
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-semibold text-slate-700">Waktu Mulai</label>
                        <input type="datetime-local" id="waktu_mulai" name="waktu_mulai"
                               value="{{ old('waktu_mulai') }}"
                               required
                               class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-red-400 focus:ring-red-200">
                    </div>

                    {{-- Waktu Selesai --}}
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-semibold text-slate-700">Waktu Selesai</label>
                        <input type="datetime-local" id="waktu_selesai" name="waktu_selesai"
                               value="{{ old('waktu_selesai') }}"
                               required
                               class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-red-400 focus:ring-red-200">
                    </div>
                </div>

                {{-- Pilih Fasilitas dengan Gambar --}}
<div>
    <label class="block text-sm font-semibold text-slate-700">Pilih Fasilitas</label>
    <div class="mt-2 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ($fasilitasList as $fasilitas)
            @php
                $imageUrl = $fasilitas->gambar_fasilitas_url ?? null;
            @endphp
            <div class="fasilitas-card cursor-pointer rounded-2xl border-2 border-slate-200 p-2 text-center transition hover:border-red-400 {{ old('fasilitas_id') == $fasilitas->id ? 'border-emerald-500 bg-emerald-50' : '' }}"
                 data-id="{{ $fasilitas->id }}"
                 onclick="selectFasilitas({{ $fasilitas->id }})">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}"
                         alt="{{ $fasilitas->nama_fasilitas }}"
                         class="h-24 w-full rounded-xl object-cover">
                @else
                    <div class="flex h-24 w-full items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <p class="mt-2 text-sm font-medium text-slate-800">{{ $fasilitas->nama_fasilitas }}</p>
            </div>
        @endforeach
    </div>
    <input type="hidden" name="fasilitas_id" id="fasilitas_id" value="{{ old('fasilitas_id') }}">
    @error('fasilitas_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
                </div>

                {{-- Kegiatan --}}
                <div>
                    <label for="kegiatan" class="block text-sm font-semibold text-slate-700">Kegiatan</label>
                    <input type="text" id="kegiatan" name="kegiatan"
                           value="{{ old('kegiatan') }}"
                           placeholder="Contoh: Rapat Tahunan, Pelatihan, dll."
                           required
                           class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-red-400 focus:ring-red-200">
                </div>

                {{-- Upload PDF (opsional) --}}
                <div>
                    <label for="dokumen_pdf" class="block text-sm font-semibold text-slate-700">Dokumen Pendukung (PDF, opsional)</label>
                    <input type="file" id="dokumen_pdf" name="dokumen_pdf"
                           accept=".pdf"
                           class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-700 hover:file:bg-red-100">
                    <p class="mt-1 text-xs text-slate-400">Maks. 2MB, format PDF.</p>
                </div>

                {{-- Tombol Submit --}}
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Booking 
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                </div>
            </form>
        </section>
    </div>

    @push('scripts')
    <script>
        function selectFasilitas(id) {
            // Set hidden input
            document.getElementById('fasilitas_id').value = id;

            // Hapus class 'selected' dari semua card
            document.querySelectorAll('.fasilitas-card').forEach(card => {
                card.classList.remove('border-emerald-500', 'bg-emerald-50');
            });

            // Tambahkan class 'selected' ke card yang dipilih
            const card = document.querySelector(`.fasilitas-card[data-id="${id}"]`);
            if (card) {
                card.classList.add('border-emerald-500', 'bg-emerald-50');
            }
        }
    </script>
    @endpush
</x-admin-layout>