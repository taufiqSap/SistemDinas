<x-admin-layout title="Tambah Tipe Sewa">
    <section class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl shadow-slate-950/30 backdrop-blur-xl sm:rounded-3xl sm:p-6">
        <div class="mb-5 sm:mb-6">
            <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-cyan-200/80 sm:text-xs">Master Data</p>
            <h2 class="mt-2 text-xl font-bold text-white sm:text-2xl">Tambah Tipe Sewa</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300">Isi data inti yang paling sering dipakai terlebih dahulu. Tampilan ini dibuat lebih nyaman untuk layar kecil.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm leading-6 text-rose-100">
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