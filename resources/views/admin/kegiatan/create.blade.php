<x-admin-layout title="Tambah Kegiatan">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Master Data</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Tambah Kegiatan</h2>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-100">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.kegiatan.form', ['action' => route('admin.kegiatan.store')])
    </section>
</x-admin-layout>
