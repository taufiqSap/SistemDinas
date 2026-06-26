<x-admin-layout title="Manajemen User">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm shadow-red-100/60">
            <!-- ... (bagian header dan form pencarian tetap sama) ... -->
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
                                <th class="px-4 py-4 font-semibold">NIK</th>
                                <th class="px-4 py-4 font-semibold">Nama</th>
                                <th class="px-4 py-4 font-semibold">Email</th>
                                <th class="px-4 py-4 font-semibold">No. HP</th>
                                <th class="px-4 py-4 font-semibold">Jenis Daftar</th>
                                <th class="px-4 py-4 font-semibold">Lembaga</th>
                                <th class="px-4 py-4 font-semibold">Alamat</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white">
                            @forelse ($users as $user)
                                @php
                                    $isActive = ($user->status ?? 'aktif') === 'aktif';
                                    $actionText = $isActive ? 'Nonaktifkan' : 'Aktifkan';
                                    $actionButtonClass = $isActive 
                                        ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' 
                                        : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100';
                                    $statusBadgeClass = $isActive 
                                        ? 'bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-200' 
                                        : 'bg-rose-100 text-rose-700 ring-1 ring-inset ring-rose-200';
                                @endphp
                                <tr class="align-top hover:bg-red-50">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $user->NIK }}</td>
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $user->nama }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $user->email }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $user->no_hp }}</td>
                                    <td class="px-4 py-4 text-slate-700">
                                        {{ ucfirst($user->jenis_daftar ?? 'perorangan') }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-700">
                                        {{ ($user->jenis_daftar ?? 'perorangan') === 'lembaga' ? ($user->nama_lembaga ?? '-') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-700 max-w-[200px] truncate" title="{{ $user->alamat }}">
                                        {{ $user->alamat }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadgeClass }}">
                                            {{ ucfirst($user->status ?? 'aktif') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        {{-- Tombol Nonaktifkan/Aktifkan --}}
                                        <button type="button" 
                                                onclick="bukaModalToggle('{{ route('admin.users.toggle-status', $user) }}', '{{ $user->nama }}', '{{ $actionText }}')"
                                                class="rounded-full border px-3 py-2 text-xs font-semibold transition {{ $actionButtonClass }}">
                                            {{ $actionText }}
                                        </button>
                                    
                                        {{-- Tombol Reset Password --}}
                                        <button type="button" 
                                                onclick="bukaModalReset('{{ route('admin.users.reset-password', $user) }}', '{{ $user->nama }}', '{{ $user->no_hp }}')"
                                                class="mt-1 rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                                            Reset Password
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-10 text-center text-slate-500">Belum ada data user.</td>
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

    {{-- ========== MODAL KONFIRMASI TOGGLE STATUS ========== --}}
    <div id="modalToggleStatus" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 id="modalToggleTitle" class="text-lg font-bold text-slate-900">Konfirmasi</h3>
                <p id="modalToggleMessage" class="mt-1 text-sm text-slate-600">Apakah Anda yakin ingin melakukan tindakan ini?</p>
            </div>
            <form id="formToggleStatus" method="POST" class="p-5">
                @csrf
                @method('PATCH')
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalToggle" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" id="btnKonfirmasiToggle" class="flex-1 rounded-full px-5 py-3 text-sm font-semibold text-white transition">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== MODAL KONFIRMASI RESET PASSWORD ========== --}}
    <div id="modalResetPassword" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 id="modalResetTitle" class="text-lg font-bold text-slate-900">Reset Password</h3>
                <p id="modalResetMessage" class="mt-1 text-sm text-slate-600">Apakah Anda yakin ingin mereset password user ini?</p>
            </div>
            <form id="formResetPassword" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btnBatalReset" class="flex-1 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // ============ TOGGLE STATUS ============
        function bukaModalToggle(actionUrl, userName, action) {
            const modal = document.getElementById('modalToggleStatus');
            const form = document.getElementById('formToggleStatus');
            const title = document.getElementById('modalToggleTitle');
            const message = document.getElementById('modalToggleMessage');
            const confirmBtn = document.getElementById('btnKonfirmasiToggle');

            form.action = actionUrl;

            if (action === 'Nonaktifkan') {
                title.textContent = 'Konfirmasi Nonaktifkan';
                message.textContent = `Apakah Anda yakin ingin menonaktifkan akun "${userName}"?`;
                confirmBtn.className = 'flex-1 rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700';
                confirmBtn.textContent = 'Nonaktifkan';
            } else {
                title.textContent = 'Konfirmasi Aktifkan';
                message.textContent = `Apakah Anda yakin ingin mengaktifkan akun "${userName}"?`;
                confirmBtn.className = 'flex-1 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700';
                confirmBtn.textContent = 'Aktifkan';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('btnBatalToggle').addEventListener('click', function() {
            document.getElementById('modalToggleStatus').classList.add('hidden');
            document.getElementById('modalToggleStatus').classList.remove('flex');
        });

        // ============ RESET PASSWORD ============
        function bukaModalReset(actionUrl, userName, noHp) {
            const modal = document.getElementById('modalResetPassword');
            const form = document.getElementById('formResetPassword');
            const title = document.getElementById('modalResetTitle');
            const message = document.getElementById('modalResetMessage');

            form.action = actionUrl;
            title.textContent = 'Reset Password';
            message.textContent = `Reset password untuk "${userName}"? Password akan diubah menjadi nomor HP (${noHp || 'tidak tersedia'}).`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('btnBatalReset').addEventListener('click', function() {
            document.getElementById('modalResetPassword').classList.add('hidden');
            document.getElementById('modalResetPassword').classList.remove('flex');
        });

        // ============ TUTUP MODAL JIKA KLIK DI LUAR ============
        document.querySelectorAll('.fixed.inset-0.z-50').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    this.classList.remove('flex');
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>