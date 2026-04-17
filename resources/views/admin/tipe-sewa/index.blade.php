<x-admin-layout title="Manajemen Tipe Sewa">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200/80">Master Data</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Tipe Sewa</h2>
                    <p class="mt-1 text-sm text-slate-400">Kelola jenis sewa yang dapat dipilih saat booking.</p>
                </div>

                <a href="{{ route('admin.tipe-sewa.create') }}" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                    Tambah Tipe Sewa
                </a>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                        <thead class="bg-white/5 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Nama</th>
                                <th class="px-4 py-4 font-semibold">Deskripsi</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 bg-slate-950/40">
                            @forelse ($tipeSewas as $tipeSewa)
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-4 font-semibold text-white">{{ $tipeSewa->nama_tipe }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $tipeSewa->deskripsi ?: '-' }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.tipe-sewa.edit', $tipeSewa) }}" class="rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-2 text-xs font-semibold text-cyan-100">Edit</a>
                                            <form method="POST" action="{{ route('admin.tipe-sewa.destroy', $tipeSewa) }}" onsubmit="return confirm('Hapus tipe sewa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full border border-rose-400/30 bg-rose-400/10 px-3 py-2 text-xs font-semibold text-rose-100">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center text-slate-400">Belum ada data tipe sewa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $tipeSewas->links() }}
            </div>
        </section>
    </div>
</x-admin-layout>