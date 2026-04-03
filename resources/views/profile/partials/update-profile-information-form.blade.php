<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="nama" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input id="nama" name="nama" type="text" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#c62828] focus:bg-white focus:ring-1 focus:ring-[#c62828]" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                @if ($errors->has('nama'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('nama') }}</p>
                @endif
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#c62828] focus:bg-white focus:ring-1 focus:ring-[#c62828]" :value="old('email', $user->email)" required autocomplete="username" />
                @if ($errors->has('email'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                @endif
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="no_hp" class="block text-sm font-semibold text-slate-700">Nomor HP</label>
                <input id="no_hp" name="no_hp" type="tel" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#c62828] focus:bg-white focus:ring-1 focus:ring-[#c62828]" :value="old('no_hp', $user->no_hp)" required autocomplete="tel" />
                @if ($errors->has('no_hp'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('no_hp') }}</p>
                @endif
            </div>
        </div>

        <div>
            <label for="alamat" class="block text-sm font-semibold text-slate-700">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#c62828] focus:bg-white focus:ring-1 focus:ring-[#c62828]" required>{{ old('alamat', $user->alamat) }}</textarea>
            @if ($errors->has('alamat'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('alamat') }}</p>
            @endif
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3.5">
                <p class="text-sm text-amber-800">
                    Email Anda belum terverifikasi.
                    <button form="send-verification" class="font-semibold text-amber-900 hover:underline">
                        Kirim ulang email verifikasi
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-semibold text-sm text-green-600">Tautan verifikasi telah dikirim ke email Anda.</p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#c62828] px-6 py-2.5 font-semibold text-white transition hover:bg-[#b71c1c]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-green-600">
                    ✓ Profil berhasil diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
