<x-admin-layout title="Manajemen User">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-500">Pengaturan Akses</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Daftar User</h2>
                    <p class="mt-1 text-sm text-slate-600">Aktifkan atau nonaktifkan user sementara dari sini.</p>
                </div>

                <form method="GET" class="flex w-full gap-2 sm:w-auto">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau nomor HP" class="w-full min-w-0 rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-red-400 focus:ring-red-200 sm:w-80">
                    <button class="inline-flex items-center justify-center rounded-full bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Cari</button>
                </form>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-red-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-red-100 text-left text-sm">
                        <thead class="bg-red-50 text-xs uppercase tracking-[0.2em] text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Nama</th>
                                <th class="px-4 py-4 font-semibold">Nomor hp</th>
                                <th class="px-4 py-4 font-semibold">Alamat</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white">
                            @forelse ($users as $user)
                                <tr class="align-top hover:bg-red-50">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $user->nama }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $user->no_hp }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $user->alamat }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$user->status] ?? 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200' }}">
                                            {{ $statusLabels[$user->status] ?? ucfirst($user->status ?? 'aktif') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" onsubmit="return confirm('{{ ($user->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-full border px-3 py-2 text-xs font-semibold transition {{ ($user->status ?? 'aktif') === 'aktif' ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                {{ ($user->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </section>
    </div>
</x-admin-layout>