@php
    $isEdit = isset($tipeSewa);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-5 sm:space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="nama_tipe" class="mb-1 block text-sm font-semibold text-slate-200">Nama Tipe</label>
        <input id="nama_tipe" name="nama_tipe" type="text" value="{{ old('nama_tipe', $tipeSewa->nama_tipe ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3.5 text-base text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" placeholder="Contoh: Villa Harian" required>
    </div>

    <div>
        <label for="deskripsi" class="mb-1 block text-sm font-semibold text-slate-200">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3.5 text-base leading-6 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" placeholder="Tulis deskripsi singkat agar mudah dibaca di HP">{{ old('deskripsi', $tipeSewa->deskripsi ?? '') }}</textarea>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.tipe-sewa.index') }}" class="inline-flex w-full items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3.5 text-sm font-semibold text-slate-200 sm:w-auto">Batal</a>
        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-cyan-400 px-5 py-3.5 text-sm font-semibold text-slate-950 sm:w-auto">Simpan</button>
    </div>
</form>