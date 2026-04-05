<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pembayaran Booking</h2>
                <p class="mt-1 text-sm text-gray-600">Scan QRIS, lakukan pembayaran, lalu kirim bukti untuk menyelesaikan booking.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                    <p class="font-semibold">Periksa kembali data pembayaran.</p>
                    <ul class="mt-2 list-disc ps-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Ringkasan Booking</h3>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Fasilitas</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $fasilitas->nama_fasilitas }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Tipe Sewa</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $tipeSewa->nama_tipe }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Kegiatan</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $kegiatan->nama_kegiatan }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Tanggal</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $validated['tanggal_sewa'] }} s/d {{ $tanggalSelesai }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Durasi</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $validated['durasi_hari'] }} hari</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Harga Satuan</p>
                                <p class="mt-1 font-semibold text-gray-900">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
                            <p class="text-sm text-emerald-800">Total pembayaran:</p>
                            <p class="text-2xl font-bold text-emerald-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Bayar via QRIS</h3>
                        <p class="mt-1 text-sm text-gray-600">Scan QRIS berikut, lalu kirim bukti pembayaran.</p>

                        <div class="mt-4 rounded-lg border border-gray-200 p-4">
                            <img id="qris-image" src="{{ $qrisUrl }}" alt="QRIS Pembayaran" class="w-full rounded-md border border-gray-100">
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ $qrisUrl }}" download="qris-booking.png" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Download QRIS
                            </a>
                            <button
                                id="btn-sudah-bayar"
                                type="button"
                                onclick="document.getElementById('modal-bukti').classList.remove('hidden'); document.getElementById('modal-bukti').classList.add('flex');"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                Sudah Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-bukti" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h4 class="text-lg font-semibold text-gray-900">Kirim Bukti Pembayaran</h4>
                <button
                    type="button"
                    id="btn-close-modal"
                    onclick="document.getElementById('modal-bukti').classList.add('hidden'); document.getElementById('modal-bukti').classList.remove('flex');"
                    class="text-gray-400 hover:text-gray-600"
                >&times;</button>
            </div>

            <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data" class="space-y-4 p-6">
                @csrf

                <input type="hidden" name="fasilitas_id" value="{{ $validated['fasilitas_id'] }}">
                <input type="hidden" name="tipe_sewa_id" value="{{ $validated['tipe_sewa_id'] }}">
                <input type="hidden" name="kegiatan_id" value="{{ $validated['kegiatan_id'] }}">
                <input type="hidden" name="tanggal_sewa" value="{{ $validated['tanggal_sewa'] }}">
                <input type="hidden" name="durasi_hari" value="{{ $validated['durasi_hari'] }}">

                <div>
                    <label for="bukti_pembayaran" class="mb-1.5 block text-sm font-medium text-gray-700">Upload bukti (jpg/jpeg/png/pdf)</label>
                    <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                </div>

                <div class="rounded-md bg-amber-50 border border-amber-200 px-3 py-2 text-xs text-amber-800">
                    Booking baru akan dibuat setelah bukti pembayaran berhasil dikirim.
                </div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <button
                        type="button"
                        id="btn-batal"
                        onclick="document.getElementById('modal-bukti').classList.add('hidden'); document.getElementById('modal-bukti').classList.remove('flex');"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >Batal</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Kirim Bukti & Buat Booking</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('modal-bukti');
            const openBtn = document.getElementById('btn-sudah-bayar');
            const closeBtn = document.getElementById('btn-close-modal');
            const cancelBtn = document.getElementById('btn-batal');

            if (!modal || !openBtn || !closeBtn || !cancelBtn) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
        })();
    </script>
@endpush
