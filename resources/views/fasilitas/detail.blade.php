@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --accent-red: #c62828;
            --text-strong: #0f172a;
            --text-soft: #475569;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 450, 'GRAD' 0, 'opsz' 24;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

<x-layout-app>
    @php
        $fasilitas = $fasilitas ?? null;
        $adminWhatsAppNumber = '6285737644100';
        $adminChatUrl = 'https://wa.me/' . $adminWhatsAppNumber . '?text=' . rawurlencode('Halo Admin, saya ingin bertanya tentang layanan penyewaan fasilitas.');

        $statusMap = [
            'available' => ['label' => 'Tersedia', 'badge' => 'bg-emerald-500', 'dot' => 'bg-emerald-500'],
            'rented' => ['label' => 'Terbooking', 'badge' => 'bg-amber-500', 'dot' => 'bg-amber-500'],
            'maintenance' => ['label' => 'Perawatan', 'badge' => 'bg-slate-500', 'dot' => 'bg-slate-500'],
        ];

        $namaFasilitas = data_get($fasilitas, 'nama_fasilitas', '');
        $kategoriNama = data_get($fasilitas, 'kategori.nama_kategori', '');
        $statusFasilitas = data_get($fasilitas, 'status_fasilitas', 'available');
        $status = $statusMap[$statusFasilitas] ?? ['label' => 'Tidak Diketahui', 'badge' => 'bg-slate-500', 'dot' => 'bg-slate-500'];
        $kapasitas = data_get($fasilitas, 'kapasitas', '');
        $deskripsi = data_get($fasilitas, 'deskripsi', '');
        $spesifikasi = data_get($fasilitas, 'spesifikasi', '');
        $alamat = data_get($fasilitas, 'alamat', '');
        $gambarUtama = data_get($fasilitas, 'gambar_fasilitas_url');
        $fasilitasId = data_get($fasilitas, 'id');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-600">
                    <span>Detail Aset</span>
                    <span class="text-slate-300">/</span>
                    <span>{{ $kategoriNama }}</span>
                </div>
                <h1 class="mt-2 break-words text-2xl font-black tracking-tight text-slate-950 sm:text-[2rem] md:text-3xl">{{ $namaFasilitas }}</h1>
                <p class="mt-1 text-sm text-slate-700">Ringkasan aset, fasilitas, dan form awal untuk proses booking.</p>
            </div>

            
        </div>
    </x-slot>

    <section class="bg-gradient-to-b from-slate-50 to-white py-5 sm:py-8">
        <div class="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div id="booking-success-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="booking-success-title">
                    <div class="w-full max-w-[92vw] overflow-hidden rounded-2xl bg-white shadow-2xl sm:max-w-md">
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:p-6">
                            <div class="flex items-center justify-center sm:block">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-12 w-12 text-emerald-600 sm:h-14 sm:w-14" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="9" stroke-width="1.5" class="text-emerald-600" fill="#ecfdf5" />
                                    <path d="M9 12.5l1.8 1.8L15 10" stroke="#059669" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1 text-center sm:text-left">
                                <h3 id="booking-success-title" class="text-base font-bold text-slate-900 sm:text-lg">Booking berhasil dibuat</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600 sm:text-[15px]">
                                    Anda akan menerima pesan konfirmasi dari admin.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 border-t border-slate-100 p-4 sm:flex-row sm:justify-end sm:p-6">
                            <a href="{{ route('fasilitas.index') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:w-auto">
                                Oke
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <p class="font-semibold">Periksa kembali data booking.</p>
                    <ul class="mt-2 list-disc space-y-1 ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           @if ($adminChatUrl)
    <a
        href="{{ $adminChatUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-5 right-5 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-300"
        aria-label="Chat admin via WhatsApp"
        title="Chat Admin"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="h-7 w-7 fill-current" aria-hidden="true">
            <path d="M19.11 17.42c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.16-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.9-.8-1.5-1.79-1.67-2.09-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5-.17 0-.37-.02-.57-.02-.2 0-.52.07-.8.37-.27.3-1.02 1-1.02 2.44s1.05 2.84 1.2 3.04c.15.2 2.07 3.16 5 4.43.7.3 1.25.48 1.68.62.7.22 1.34.19 1.85.11.56-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.18-1.42-.07-.11-.27-.18-.57-.33zM16.01 5.33c-5.9 0-10.7 4.8-10.7 10.7 0 1.88.49 3.72 1.42 5.33L6 26.67l5.45-1.4c1.56.85 3.31 1.3 5.07 1.3h.01c5.9 0 10.7-4.8 10.7-10.7 0-2.86-1.11-5.55-3.13-7.57a10.63 10.63 0 0 0-7.59-2.97zm0 19.48h-.01a8.7 8.7 0 0 1-4.44-1.22l-.32-.19-3.23.83.86-3.14-.21-.33a8.69 8.69 0 0 1-1.34-4.63c0-4.79 3.89-8.68 8.68-8.68 2.32 0 4.5.9 6.14 2.54a8.62 8.62 0 0 1 2.54 6.14c0 4.79-3.89 8.68-8.67 8.68z" />
        </svg>
    </a>
@endif

            <div class="grid grid-cols-1 gap-5 sm:gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8 space-y-8">
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_12px_40px_rgba(15,23,42,0.08)] sm:rounded-3xl">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100 sm:aspect-[16/9]">
                            <img src="{{ $gambarUtama }}" alt="{{ $namaFasilitas }}" class="h-full w-full object-cover transition duration-700 hover:scale-105">
                            <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em] text-white {{ $status['badge'] }}">
                                    {{ $status['label'] }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em] text-slate-700 backdrop-blur">
                                    {{ $kategoriNama }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 hidden rounded-full bg-slate-900/90 px-4 py-2 text-xs font-semibold text-white backdrop-blur sm:block">
                                Siap dipakai untuk proses booking
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 md:p-8">
                            <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 md:flex-row md:items-start md:justify-between">
                                <div class="max-w-3xl">
                                    <p class="text-xs font-black uppercase tracking-[0.25em] text-[var(--accent-red)]">{{ $kategoriNama }}</p>
                                    <h2 class="mt-2 break-words text-2xl font-black tracking-tight text-[var(--text-strong)] sm:text-3xl md:text-4xl">{{ $namaFasilitas }}</h2>
                                    <p class="mt-3 flex items-start gap-2 text-sm leading-6 text-[var(--text-soft)] md:text-base">
                                        <span class="material-symbols-outlined mt-0.5 text-[18px] text-[#c62828]">location_on</span>
                                        {{ $alamat }}
                                    </p>
                                </div>

                    
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                                <div class="lg:col-span-2 space-y-8">
                                    <section>
                                        <h3 class="flex items-center gap-2 text-lg font-black text-slate-950">
                                            <span class="h-5 w-1 rounded-full bg-[#c62828]"></span>
                                            Deskripsi Aset
                                        </h3>
                                        <div class="mt-4 space-y-4 text-sm leading-7 text-slate-700 md:text-base">
                                            <p>{{ $deskripsi }}</p>
                                            <p>{{ $spesifikasi }}</p>
                                            <p><span class="font-semibold text-slate-900">Kapasitas:</span> {{ $kapasitas }}</p>
                                        </div>
                                    </section>

                                </div>

                                <aside class="space-y-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:sticky lg:top-24 sm:rounded-3xl sm:p-5">
                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                                        <p class="font-bold">Catatan booking</p>
                                        <p class="mt-2 break-words leading-6">Booking anda dapat disesuaikan jika ada agenda dinas mendadak.</p>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="lg:col-span-4">
                    <div id="form-booking" class="space-y-6 lg:sticky lg:top-24">
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_12px_40px_rgba(15,23,42,0.08)] sm:rounded-3xl">
                            <div class="border-b border-slate-100 p-5 sm:p-6">
                                <div class="flex items-baseline justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Booking Awal</p>
                                        <h3 class="mt-2 text-xl font-black text-slate-950">Form Permohonan Sewa</h3>
                                    </div>
                                    <div class="text-right">
                                        
                                    </div>
                                </div>
                            </div>

                            @if ($statusFasilitas !== 'maintenance')
                                <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data" class="space-y-5 p-5 sm:p-6">
                                     @csrf

                                     <input type="hidden" name="fasilitas_id" value="{{ old('fasilitas_id', $fasilitasId) }}">

                                     <div>
                                         <label for="kegiatan" class="mb-1.5 block text-sm font-bold text-slate-800">Nama Kegiatan</label>
                                         <input id="kegiatan" name="kegiatan" type="text" value="{{ old('kegiatan') }}" placeholder="Contoh: Rapat Koordinasi Tahunan" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-[#c62828] focus:ring-[#c62828]" required minlength="5">
                                     </div>

                                     <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                         <div>
                                             <label for="waktu_mulai" class="mb-1.5 block text-sm font-bold text-slate-800">Waktu Mulai</label>
                                             <input id="waktu_mulai" name="waktu_mulai" type="datetime-local" value="{{ old('waktu_mulai') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                         </div>

                                         <div>
                                             <label for="waktu_selesai" class="mb-1.5 block text-sm font-bold text-slate-800">Waktu Selesai</label>
                                             <input id="waktu_selesai" name="waktu_selesai" type="datetime-local" value="{{ old('waktu_selesai') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                         </div>
                                     </div>

                                     <div>
                                         <label for="dokumen_pdf" class="mb-1.5 block text-sm font-bold text-slate-800">Dokumen Pengajuan (PDF)</label>
                                         <input id="dokumen_pdf" name="dokumen_pdf" type="file" accept="application/pdf" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                         <p class="mt-2 text-xs text-slate-600">Format wajib .pdf, maksimal ukuran 2MB.</p>
                                     </div>

                                     <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#c62828] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-[#c62828]/20 transition hover:bg-[#b71c1c]">
                                         <span class="material-symbols-outlined">send</span>
                                         Ajukan Booking 
                                     </button>
                                </form>
                            @else
                        <div class="space-y-5 p-5 sm:p-6">
                            <input type="hidden" name="fasilitas_id" value="{{ $fasilitasId }}">

                            <div>
                                <label class="mb-1.5 block text-sm font-bold text-slate-800">Nama Kegiatan</label>
                                <div class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500">-</div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-bold text-slate-800">Waktu Mulai</label>
                                    <input type="datetime-local" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-bold text-slate-800">Waktu Selesai</label>
                                    <input type="datetime-local" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-bold text-slate-800">Dokumen Pengajuan (PDF)</label>
                                <input type="file" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500">
                                <p class="mt-2 text-xs text-slate-600">Fasilitas sedang menjalani perawatan.</p>
                            </div>

                            <button type="button" disabled class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-400 px-5 py-3.5 text-sm font-black text-white shadow-sm">
                                Sedang dalam perawatan
                            </button>
                        </div>
                        @endif
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:rounded-3xl sm:p-5">
                            <h4 class="flex items-center gap-2 text-sm font-black text-slate-950">
                                <span class="material-symbols-outlined text-[#c62828]">gavel</span>
                                Ketentuan Singkat
                            </h4>
                            <ul class="mt-4 space-y-3 text-sm text-slate-700">
                
                                <li class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-400"></span>
                                    Dilarang merusak properti gedung.
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-400"></span>
                                    Izin keramaian diurus oleh penyewa.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout-app>

@push('scripts')
    <script>
        (function () {
            const successPopup = document.getElementById('booking-success-popup');
            const fasilitasUrl = @json(route('fasilitas.index'));

            if (successPopup) {
                successPopup.addEventListener('click', (event) => {
                    // Tutup modal jika user mengklik area di luar kotak modal
                    if (event.target === successPopup) {
                        window.location.href = fasilitasUrl;
                    }
                });
            }
        })();
    </script>
@endpush