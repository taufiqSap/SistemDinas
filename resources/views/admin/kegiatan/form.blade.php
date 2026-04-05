@php
    $isEdit = isset($kegiatan);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div class="md:col-span-2">
            <label for="nama_kegiatan" class="mb-1 block text-sm font-semibold text-slate-200">Nama Kegiatan</label>
            <input id="nama_kegiatan" name="nama_kegiatan" type="text" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>
        </div>

        <div class="md:col-span-2">
            <label for="deskripsi" class="mb-1 block text-sm font-semibold text-slate-200">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400">{{ old('deskripsi', $kegiatan->deskripsi ?? '') }}</textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.kegiatan.index') }}" class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200">Batal</a>
        <button type="submit" class="rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950">Simpan</button>
    </div>
</form>
