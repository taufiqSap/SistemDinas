<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Booking</h2>
                <p class="mt-1 text-sm text-gray-600">Pilih fasilitas dan kegiatan dari master data.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                                            Tunggu konfirmasi admin. Anda akan mendapatkan notifikasi WA.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 border-t border-slate-100 p-4 sm:flex-row sm:justify-end sm:p-6">
                                    <button id="close-booking-success-popup" type="button" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:w-auto">
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

                    <form id="booking-form" method="POST" action="{{ route('booking.store') }}" class="space-y-6">
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
                            <x-primary-button id="submit-booking-btn">Ajukan Booking Gratis</x-primary-button>
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
            const tanggalSewa = document.getElementById('tanggal_sewa');
            const durasiHari = document.getElementById('durasi_hari');
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const bookingForm = document.getElementById('booking-form');
            const submitBookingBtn = document.getElementById('submit-booking-btn');

            if (!fasilitasId || !tanggalSewa || !durasiHari || !tanggalSelesai) {
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
            const fasilitasUrl = @json(route('fasilitas.index'));

            if (successPopup && closeSuccessPopupBtn) {
                closeSuccessPopupBtn.addEventListener('click', () => {
                    window.location.href = fasilitasUrl;
                });
            }

            if (bookingForm && submitBookingBtn) {
                bookingForm.addEventListener('submit', () => {
                    submitBookingBtn.setAttribute('disabled', 'disabled');
                    submitBookingBtn.classList.add('opacity-60', 'cursor-not-allowed');
                    submitBookingBtn.textContent = 'Memproses...';
                }, { once: true });
            }
        })();
    </script>
@endpush