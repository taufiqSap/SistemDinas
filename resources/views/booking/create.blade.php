<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Booking</h2>
                <p class="mt-1 text-sm text-gray-600">Pilih fasilitas, tipe sewa, dan kegiatan dari master data.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div id="booking-success-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-0.5 text-3xl text-emerald-600">check_circle</span>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Booking berhasil dibuat</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                            Tunggu konfirmasi admin. Anda akan mendapatkan notifikasi WA.
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 flex justify-end">
                                    <button id="close-booking-success-popup" type="button" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                        Oke
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                            <p class="font-semibold">Periksa kembali data booking.</p>
                            <ul class="mt-2 list-disc ps-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="fasilitas_id" value="Fasilitas" />
                                <select id="fasilitas_id" name="fasilitas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih fasilitas</option>
                                    @foreach ($fasilitass as $fasilitas)
                                        <option value="{{ $fasilitas->id }}" @selected(old('fasilitas_id', request('fasilitas_id')) == $fasilitas->id)>{{ $fasilitas->nama_fasilitas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="tipe_sewa_id" value="Tipe Sewa" />
                                <select id="tipe_sewa_id" name="tipe_sewa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih tipe sewa</option>
                                    @foreach ($tipeSewas as $tipeSewa)
                                        <option value="{{ $tipeSewa->id }}" @selected(old('tipe_sewa_id') == $tipeSewa->id)>{{ $tipeSewa->nama_tipe }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="kegiatan_id" value="Kegiatan" />
                                <select id="kegiatan_id" name="kegiatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih kegiatan</option>
                                    @foreach ($kegiatans as $kegiatan)
                                        <option value="{{ $kegiatan->id }}" @selected(old('kegiatan_id') == $kegiatan->id)>{{ $kegiatan->nama_kegiatan }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Dropdown ini mengambil data dari tabel master kegiatan.</p>
                            </div>

                            <div>
                                <x-input-label for="tanggal_sewa" value="Tanggal Sewa" />
                                <x-text-input id="tanggal_sewa" name="tanggal_sewa" type="date" class="mt-1 block w-full" :value="old('tanggal_sewa')" required />
                            </div>

                            <div>
                                <x-input-label for="durasi_hari" value="Durasi Hari" />
                                <x-text-input id="durasi_hari" name="durasi_hari" type="number" min="1" class="mt-1 block w-full" :value="old('durasi_hari', 1)" required />
                            </div>

                            <div>
                                <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
                                <x-text-input id="tanggal_selesai" type="date" class="mt-1 block w-full bg-gray-100" :value="old('tanggal_selesai')" readonly />
                                <p class="mt-2 text-sm text-gray-500">Tanggal selesai dihitung otomatis dari tanggal sewa dan durasi.</p>
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>Ajukan Booking Gratis</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        (function () {
            const fasilitasId = document.getElementById('fasilitas_id');
            const tipeSewaId = document.getElementById('tipe_sewa_id');
            const tanggalSewa = document.getElementById('tanggal_sewa');
            const durasiHari = document.getElementById('durasi_hari');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            if (!fasilitasId || !tipeSewaId || !tanggalSewa || !durasiHari || !tanggalSelesai) {
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

            tanggalSewa.addEventListener('change', setTanggalSelesai);
            durasiHari.addEventListener('input', setTanggalSelesai);
            setTanggalSelesai();

            const successPopup = document.getElementById('booking-success-popup');
            const closeSuccessPopupBtn = document.getElementById('close-booking-success-popup');

            if (successPopup && closeSuccessPopupBtn) {
                const closePopup = () => {
                    successPopup.classList.add('hidden');
                };

                closeSuccessPopupBtn.addEventListener('click', closePopup);
                successPopup.addEventListener('click', (event) => {
                    if (event.target === successPopup) {
                        closePopup();
                    }
                });
            }
        })();
    </script>
@endpush