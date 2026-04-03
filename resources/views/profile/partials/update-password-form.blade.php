<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="rounded-lg border border-blue-200 bg-blue-50 p-3.5">
            <p class="text-sm text-blue-900">Buat kata sandi yang kuat dengan minimal 8 karakter, kombinasi huruf besar, angka, dan simbol untuk keamanan maksimal.</p>
        </div>

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600" autocomplete="current-password" />
            @if ($errors->updatePassword->has('current_password'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-slate-700">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600" autocomplete="new-password" />
            @if ($errors->updatePassword->has('password'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600" autocomplete="new-password" />
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-semibold text-white transition hover:bg-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-green-600">
                    ✓ Kata sandi berhasil diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
