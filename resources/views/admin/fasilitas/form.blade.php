@php
    $isEdit = isset($fasilitas);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="kategori_id" class="mb-1 block text-sm font-semibold text-slate-200">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>
                <option value="">Pilih kategori</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('kategori_id', $fasilitas->kategori_id ?? '') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="nama_fasilitas" class="mb-1 block text-sm font-semibold text-slate-200">Nama Fasilitas</label>
            <input id="nama_fasilitas" name="nama_fasilitas" type="text" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>
        </div>

        <div>
            <label for="kapasitas" class="mb-1 block text-sm font-semibold text-slate-200">Kapasitas</label>
            <input id="kapasitas" name="kapasitas" type="text" value="{{ old('kapasitas', $fasilitas->kapasitas ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>
        </div>

        <div>
            <label for="status_fasilitas" class="mb-1 block text-sm font-semibold text-slate-200">Status Fasilitas</label>
            <select id="status_fasilitas" name="status_fasilitas" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}" @selected(old('status_fasilitas', $fasilitas->status_fasilitas ?? 'available') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label for="deskripsi" class="mb-1 block text-sm font-semibold text-slate-200">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400">{{ old('deskripsi', $fasilitas->deskripsi ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="spesifikasi" class="mb-1 block text-sm font-semibold text-slate-200">Spesifikasi</label>
            <textarea id="spesifikasi" name="spesifikasi" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" required>{{ old('spesifikasi', $fasilitas->spesifikasi ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="gambar_fasilitas" class="mb-1 block text-sm font-semibold text-slate-200">Gambar Fasilitas</label>
            <input id="gambar_fasilitas" name="gambar_fasilitas" type="text" value="{{ old('gambar_fasilitas', $fasilitas->gambar_fasilitas ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" placeholder="contoh: images/fasilitas/gambar.jpg">
            <p class="mt-2 text-xs text-slate-400">Isi path gambar atau URL gambar jika ada.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-white">Harga per Tipe Sewa</h3>
            <p class="text-sm text-slate-400">Isi harga untuk tiap tipe sewa agar booking user bisa dihitung otomatis.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($tipeSewas as $tipeSewa)
                <div>
                    <label for="harga_per_tipe_{{ $tipeSewa->id }}" class="mb-1 block text-sm font-semibold text-slate-200">{{ $tipeSewa->nama_tipe }}</label>
                    <input id="harga_per_tipe_{{ $tipeSewa->id }}" name="harga_per_tipe[{{ $tipeSewa->id }}]" type="number" min="0" step="0.01" value="{{ old('harga_per_tipe.' . $tipeSewa->id, data_get($hargaPerTipe ?? [], $tipeSewa->id)) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400" placeholder="0">
                </div>
            @empty
                <p class="text-sm text-slate-400">Tipe sewa belum tersedia. Tambahkan tipe sewa dulu.</p>
            @endforelse
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.fasilitas.index') }}" class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200">Batal</a>
        <button type="submit" class="rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950">Simpan</button>
    </div>
</form>