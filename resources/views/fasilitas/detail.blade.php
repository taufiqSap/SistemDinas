@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
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

        $statusMap = [
            'available' => ['label' => 'Tersedia', 'badge' => 'bg-emerald-500', 'dot' => 'bg-emerald-500'],
            'rented' => ['label' => 'Terbooking', 'badge' => 'bg-amber-500', 'dot' => 'bg-amber-500'],
            'maintenance' => ['label' => 'Perawatan', 'badge' => 'bg-slate-500', 'dot' => 'bg-slate-500'],
        ];

        $namaFasilitas = data_get($fasilitas, 'nama_fasilitas', 'Gedung Kesenian Aryo Blitar');
        $kategoriNama = data_get($fasilitas, 'kategori.nama_kategori', 'Gedung Pertemuan');
        $statusFasilitas = data_get($fasilitas, 'status_fasilitas', 'available');
        $status = $statusMap[$statusFasilitas] ?? ['label' => 'Tidak Diketahui', 'badge' => 'bg-slate-500', 'dot' => 'bg-slate-500'];
        $kapasitas = data_get($fasilitas, 'kapasitas', '500 Orang');
        $deskripsi = data_get($fasilitas, 'deskripsi', 'Gedung representatif untuk acara resmi, pertunjukan, dan kegiatan masyarakat dengan akses mudah serta fasilitas yang memadai.');
        $spesifikasi = data_get($fasilitas, 'spesifikasi', 'Luas area representatif, area parkir memadai, sistem tata suara, pencahayaan, dan akses utama yang mudah dijangkau.');
        $gambarUtama = data_get($fasilitas, 'gambar_fasilitas');
        $gambarUtama = $gambarUtama ? asset($gambarUtama) : 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1400&q=80';
        $fasilitasId = data_get($fasilitas, 'id');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">
                    <span>Detail Aset</span>
                    <span class="text-slate-300">/</span>
                    <span>{{ $kategoriNama }}</span>
                </div>
                <h1 class="mt-2 text-2xl font-black tracking-tight text-slate-900 md:text-3xl">{{ $namaFasilitas }}</h1>
                <p class="mt-1 text-sm text-slate-600">Ringkasan aset, fasilitas, dan form awal untuk proses booking.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('fasilitas.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Kembali ke Daftar
                </a>
                <a href="#form-booking" class="inline-flex items-center justify-center rounded-full bg-[#c62828] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#b71c1c]">
                    Mulai Booking
                </a>
            </div>
        </div>
    </x-slot>

    <section class="bg-gradient-to-b from-slate-50 to-white py-8">
        <div class="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Periksa kembali data booking.</p>
                    <ul class="mt-2 list-disc space-y-1 ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8 space-y-8">
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_12px_40px_rgba(15,23,42,0.08)]">
                        <div class="relative aspect-[16/9] overflow-hidden bg-slate-100">
                            <img src="{{ $gambarUtama }}" alt="{{ $namaFasilitas }}" class="h-full w-full object-cover transition duration-700 hover:scale-105">
                            <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em] text-white {{ $status['badge'] }}">
                                    {{ $status['label'] }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em] text-slate-700 backdrop-blur">
                                    {{ $kategoriNama }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 rounded-full bg-slate-900/80 px-4 py-2 text-xs font-semibold text-white backdrop-blur">
                                Siap dipakai untuk proses booking
                            </div>
                        </div>

                        <div class="p-6 md:p-8">
                            <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 md:flex-row md:items-start md:justify-between">
                                <div class="max-w-3xl">
                                    <p class="text-xs font-black uppercase tracking-[0.25em] text-[#c62828]">{{ $kategoriNama }}</p>
                                    <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 md:text-4xl">{{ $namaFasilitas }}</h2>
                                    <p class="mt-3 flex items-start gap-2 text-sm leading-6 text-slate-600 md:text-base">
                                        <span class="material-symbols-outlined mt-0.5 text-[18px] text-[#c62828]">location_on</span>
                                        {{ data_get($fasilitas, 'alamat', 'Jl. Kenari No. 12, Plosokerep, Kec. Sananwetan, Kota Blitar') }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:w-auto">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Kapasitas</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900">{{ $kapasitas }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Durasi</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900">Fleksibel</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Area</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900">Representatif</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                                <div class="lg:col-span-2 space-y-8">
                                    <section>
                                        <h3 class="flex items-center gap-2 text-lg font-black text-slate-900">
                                            <span class="h-5 w-1 rounded-full bg-[#c62828]"></span>
                                            Deskripsi Aset
                                        </h3>
                                        <div class="mt-4 space-y-4 text-sm leading-7 text-slate-600 md:text-base">
                                            <p>{{ $deskripsi }}</p>
                                            <p>{{ $spesifikasi }}</p>
                                        </div>
                                    </section>

                                    <section>
                                        <h3 class="flex items-center gap-2 text-lg font-black text-slate-900">
                                            <span class="h-5 w-1 rounded-full bg-[#c62828]"></span>
                                            Fasilitas Utama
                                        </h3>

                                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                                <div class="rounded-xl bg-red-50 p-3 text-[#c62828]">
                                                    <span class="material-symbols-outlined">ac_unit</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900">Pendingin Ruangan</h4>
                                                    <p class="mt-1 text-xs leading-5 text-slate-500">Area utama nyaman untuk kegiatan formal dan nonformal.</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                                <div class="rounded-xl bg-red-50 p-3 text-[#c62828]">
                                                    <span class="material-symbols-outlined">speaker</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900">Sistem Audio</h4>
                                                    <p class="mt-1 text-xs leading-5 text-slate-500">Mendukung seminar, rapat, dan acara pertunjukan.</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                                <div class="rounded-xl bg-red-50 p-3 text-[#c62828]">
                                                    <span class="material-symbols-outlined">podium</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900">Panggung / Area Utama</h4>
                                                    <p class="mt-1 text-xs leading-5 text-slate-500">Cocok untuk kegiatan seremonial maupun hiburan.</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                                <div class="rounded-xl bg-red-50 p-3 text-[#c62828]">
                                                    <span class="material-symbols-outlined">local_parking</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900">Area Parkir</h4>
                                                    <p class="mt-1 text-xs leading-5 text-slate-500">Mendukung mobilitas tamu dan panitia acara.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <section>
                                        <h3 class="flex items-center gap-2 text-lg font-black text-slate-900">
                                            <span class="h-5 w-1 rounded-full bg-[#c62828]"></span>
                                            Galeri Singkat
                                        </h3>

                                        <div class="mt-4 flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                                            @for ($i = 0; $i < 4; $i++)
                                                <div class="h-24 w-36 flex-none overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                                    <img src="{{ $gambarUtama }}" alt="{{ $namaFasilitas }} {{ $i + 1 }}" class="h-full w-full object-cover">
                                                </div>
                                            @endfor
                                        </div>
                                    </section>
                                </div>

                                <aside class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                                        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                            <span class="text-sm font-semibold text-slate-500">Status Aset</span>
                                            <span class="inline-flex items-center gap-2 text-sm font-bold text-slate-900">
                                                <span class="h-2.5 w-2.5 rounded-full {{ $status['dot'] }}"></span>
                                                {{ $status['label'] }}
                                            </span>
                                        </div>
                                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                                            <div class="flex items-center justify-between">
                                                <span>Kategori</span>
                                                <span class="font-bold text-slate-900">{{ $kategoriNama }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span>Kapasitas</span>
                                                <span class="font-bold text-slate-900">{{ $kapasitas }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span>Harga Mulai</span>
                                                <span class="font-black text-[#c62828]">Rp {{ number_format((float) data_get($fasilitas, 'harga_mulai', 1500000), 0, ',', '.') }}/hari</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                                        <p class="font-bold">Catatan booking</p>
                                        <p class="mt-2 leading-6">Harga final dapat menyesuaikan tipe sewa, kegiatan, dan kebutuhan tambahan. Form di bawah mengarahkan proses booking awal.</p>
                                    </div>

                                    <a href="#form-booking" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#c62828] px-5 py-3 text-sm font-black text-white transition hover:bg-[#b71c1c]">
                                        <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
                                        Ajukan Booking
                                    </a>
                                </aside>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="lg:col-span-4">
                    <div id="form-booking" class="sticky top-24 space-y-6">
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_12px_40px_rgba(15,23,42,0.08)]">
                            <div class="border-b border-slate-100 p-6">
                                <div class="flex items-baseline justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">Booking Awal</p>
                                        <h3 class="mt-2 text-xl font-black text-slate-900">Form Permohonan Sewa</h3>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-slate-400">Mulai dari</p>
                                        <p class="text-2xl font-black text-[#c62828]">Rp {{ number_format((float) data_get($fasilitas, 'harga_mulai', 1500000), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('booking.store') }}" class="space-y-5 p-6">
                                @csrf

                                <input type="hidden" name="fasilitas_id" value="{{ old('fasilitas_id', $fasilitasId) }}">

                                <div>
                                    <label for="tipe_sewa_id" class="mb-1.5 block text-sm font-bold text-slate-700">Tipe Sewa</label>
                                    <select id="tipe_sewa_id" name="tipe_sewa_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                        <option value="">Pilih tipe sewa</option>
                                        @forelse (($tipeSewas ?? []) as $tipeSewa)
                                            <option value="{{ $tipeSewa->id }}" @selected(old('tipe_sewa_id') == $tipeSewa->id)>{{ $tipeSewa->nama_tipe }}</option>
                                        @empty
                                            <option value="" disabled>Data tipe sewa belum tersedia</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div>
                                    <label for="kegiatan_id" class="mb-1.5 block text-sm font-bold text-slate-700">Kegiatan</label>
                                    <select id="kegiatan_id" name="kegiatan_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                        <option value="">Pilih kegiatan</option>
                                        @forelse (($kegiatans ?? []) as $kegiatan)
                                            <option value="{{ $kegiatan->id }}" @selected(old('kegiatan_id') == $kegiatan->id)>{{ $kegiatan->nama_kegiatan }}</option>
                                        @empty
                                            <option value="" disabled>Data kegiatan belum tersedia</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="tanggal_sewa" class="mb-1.5 block text-sm font-bold text-slate-700">Tanggal Mulai</label>
                                        <input id="tanggal_sewa" name="tanggal_sewa" type="date" value="{{ old('tanggal_sewa') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                    </div>

                                    <div>
                                        <label for="durasi_hari" class="mb-1.5 block text-sm font-bold text-slate-700">Durasi Hari</label>
                                        <input id="durasi_hari" name="durasi_hari" type="number" min="1" value="{{ old('durasi_hari', 1) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#c62828] focus:ring-[#c62828]" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="tanggal_selesai" class="mb-1.5 block text-sm font-bold text-slate-700">Tanggal Selesai</label>
                                        <input id="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-800" readonly>
                                        <p class="mt-2 text-xs text-slate-500">Tanggal selesai dihitung otomatis dari tanggal mulai dan durasi.</p>
                                    </div>

                                    <div>
                                        <label for="total_harga" class="mb-1.5 block text-sm font-bold text-slate-700">Total Harga</label>
                                        <input id="total_harga" name="total_harga" type="number" step="0.01" min="0" value="{{ old('total_harga') }}" placeholder="1500000" class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-800" readonly>
                                        <p class="mt-2 text-xs text-slate-500">Total harga dihitung otomatis dari tipe sewa dan durasi.</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 text-xs leading-6 text-slate-500">
                                    Pembayaran DP minimal 30% saat booking. Harga akhir dapat menyesuaikan kebutuhan tambahan dan verifikasi admin.
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#c62828] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-[#c62828]/20 transition hover:bg-[#b71c1c]">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Kirim Permohonan Booking
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h4 class="flex items-center gap-2 text-sm font-black text-slate-900">
                                <span class="material-symbols-outlined text-[#c62828]">gavel</span>
                                Ketentuan Singkat
                            </h4>
                            <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                <li class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                    Pembayaran DP minimal 30% saat booking.
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                    Dilarang merusak properti gedung.
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
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
            const hargaPerTipe = @json($hargaPerTipe ?? []);
            const tanggalSewa = document.getElementById('tanggal_sewa');
            const durasiHari = document.getElementById('durasi_hari');
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const tipeSewaId = document.getElementById('tipe_sewa_id');
            const totalHarga = document.getElementById('total_harga');

            if (!tanggalSewa || !durasiHari || !tanggalSelesai || !tipeSewaId || !totalHarga) {
                return;
            }

            const setTanggalSelesai = () => {
                if (!tanggalSewa.value || !durasiHari.value) {
                    tanggalSelesai.value = '';
                    return;
                }

                const durasi = parseInt(durasiHari.value, 10);
                if (Number.isNaN(durasi) || durasi < 1) {
                    tanggalSelesai.value = '';
                    return;
                }

                const end = new window['Date'](tanggalSewa.value + 'T00:00:00');
                end.setDate(end.getDate() + durasi - 1);
                tanggalSelesai.value = end.toISOString().slice(0, 10);
            };

            const setTotalHarga = () => {
                const hargaSatuan = Number(hargaPerTipe[tipeSewaId.value] ?? 0);
                const durasi = parseInt(durasiHari.value, 10);

                if (!tipeSewaId.value || Number.isNaN(durasi) || durasi < 1 || hargaSatuan <= 0) {
                    totalHarga.value = '';
                    return;
                }

                totalHarga.value = (hargaSatuan * durasi).toFixed(2);
            };

            tanggalSewa.addEventListener('change', setTanggalSelesai);
            durasiHari.addEventListener('input', setTanggalSelesai);
            tipeSewaId.addEventListener('change', setTotalHarga);
            durasiHari.addEventListener('input', setTotalHarga);
            setTanggalSelesai();
            setTotalHarga();
        })();
    </script>
@endpush