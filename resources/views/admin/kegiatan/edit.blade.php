<x-admin-layout title="Edit Kegiatan">
    <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Master Data</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Edit Kegiatan</h2>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.kegiatan.form', ['action' => route('admin.kegiatan.update', $kegiatan), 'kegiatan' => $kegiatan])
    </section>
</x-admin-layout>
