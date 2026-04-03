<section class="space-y-5">
    <div class="rounded-lg border border-red-200 bg-red-50 p-3.5">
        <p class="text-sm text-red-900">
            Peringatan: Setelah akun dihapus, semua data dan sumber daya Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan. Pastikan Anda telah mengunduh data penting sebelum melanjutkan.
        </p>
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 font-semibold text-white transition hover:bg-red-700"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6 p-6">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-xl font-bold text-red-900">Konfirmasi Penghapusan Akun</h2>
                <p class="mt-3 text-sm text-red-800">
                    Tindakan ini akan menghapus akun dan semua data Anda secara permanen. Masukkan kata sandi untuk mengkonfirmasi penghapusan.
                </p>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-red-600 focus:bg-white focus:ring-1 focus:ring-red-600"
                    placeholder="Masukkan kata sandi Anda"
                />
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 font-semibold text-white transition hover:bg-red-700"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
