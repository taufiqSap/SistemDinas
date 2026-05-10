<x-admin-layout title="Tambah Tipe Sewa">
    <section class="rounded-2xl border border-red-100 bg-white p-4 shadow-sm shadow-red-100/60 sm:rounded-3xl sm:p-6">
        <div class="mb-5 sm:mb-6">
            <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-red-500 sm:text-xs">Master Data</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950 sm:text-2xl">Tambah Tipe Sewa</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-700">Isi data inti yang paling sering dipakai terlebih dahulu. Tampilan ini dibuat lebih nyaman untuk layar kecil.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm leading-6 text-rose-800">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.tipe-sewa.form', ['action' => route('admin.tipe-sewa.store')])
    </section>
</x-admin-layout>