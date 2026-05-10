@php
    $isEdit = isset($fasilitas);
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="kategori_id" class="mb-1 block text-sm font-semibold text-slate-800">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:ring-red-200" required>
                <option value="">Pilih kategori</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('kategori_id', $fasilitas->kategori_id ?? '') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="nama_fasilitas" class="mb-1 block text-sm font-semibold text-slate-800">Nama Fasilitas</label>
            <input id="nama_fasilitas" name="nama_fasilitas" type="text" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas ?? '') }}" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200" required>
        </div>

        <div>
            <label for="kapasitas" class="mb-1 block text-sm font-semibold text-slate-800">Kapasitas</label>
            <input id="kapasitas" name="kapasitas" type="text" value="{{ old('kapasitas', $fasilitas->kapasitas ?? '') }}" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200" required>
        </div>

        <div>
            <label for="status_fasilitas" class="mb-1 block text-sm font-semibold text-slate-800">Status Fasilitas</label>
            <select id="status_fasilitas" name="status_fasilitas" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:ring-red-200" required>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}" @selected(old('status_fasilitas', $fasilitas->status_fasilitas ?? 'available') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label for="deskripsi" class="mb-1 block text-sm font-semibold text-slate-800">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200">{{ old('deskripsi', $fasilitas->deskripsi ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="spesifikasi" class="mb-1 block text-sm font-semibold text-slate-800">Spesifikasi</label>
            <textarea id="spesifikasi" name="spesifikasi" rows="4" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200" required>{{ old('spesifikasi', $fasilitas->spesifikasi ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="alamat" class="mb-1 block text-sm font-semibold text-slate-800">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-red-500 focus:ring-red-200" required>{{ old('alamat', $fasilitas->alamat ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="gambar_fasilitas" class="mb-1 block text-sm font-semibold text-slate-800">Gambar Fasilitas</label>
            <input id="gambar_fasilitas" name="gambar_fasilitas" type="file" accept="image/*" class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 file:mr-4 file:rounded-full file:border-0 file:bg-red-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-700 focus:border-red-500 focus:ring-red-200">
            <p class="mt-2 text-xs text-slate-600">Upload file gambar baru jika ingin mengganti gambar fasilitas.</p>

            @if (! empty($fasilitas->gambar_fasilitas))
                <div class="mt-4 overflow-hidden rounded-2xl border border-red-100 bg-white shadow-sm">
                    <div class="px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-red-500">Gambar Saat Ini</div>
                    <img src="{{ $fasilitas->gambar_fasilitas_url }}" alt="Gambar fasilitas" class="h-56 w-full object-cover">
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.fasilitas.index') }}" class="rounded-full border border-red-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-red-50">Batal</a>
        <button type="submit" class="rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Simpan</button>
    </div>
</form>