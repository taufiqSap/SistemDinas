<x-admin-layout title="Manajemen Fasilitas">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Master Data</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">Fasilitas</h2>
                    <p class="mt-1 text-sm text-slate-700">Kelola fasilitas dan status ketersediaan.</p>
                </div>

                <a href="{{ route('admin.fasilitas.create') }}" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Tambah Fasilitas
                </a>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-red-100">
                <div class="p-4 sm:p-6">
                    <form method="GET" class="flex w-full gap-2">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, kategori, atau kapasitas" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200">
                        <button class="hidden sm:inline-flex rounded-full bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Cari</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-red-100 text-left text-sm">
                        <thead class="bg-red-50 text-xs uppercase tracking-[0.2em] text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Nama</th>
                                <th class="px-4 py-4 font-semibold">Kategori</th>
                                <th class="px-4 py-4 font-semibold">Kapasitas</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white">
                            @forelse ($fasilitas as $item)
                                <tr class="hover:bg-red-50">
                                    <td class="px-4 py-4 font-semibold text-slate-950">{{ $item->nama_fasilitas }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $item->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $item->kapasitas }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ ucfirst($item->status_fasilitas) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.fasilitas.edit', $item->id) }}" class="rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Edit</a>
                                            <form method="POST" action="{{ route('admin.fasilitas.destroy', $item->id) }}" onsubmit="return confirm('Hapus fasilitas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada data fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $fasilitas->links() }}
            </div>
        </section>
    </div>
</x-admin-layout>