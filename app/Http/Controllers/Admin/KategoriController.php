<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
        public function index(): View
        {
            $query = Kategori::query()->latest();

            if ($q = request('q')) {
                $query->where('nama_kategori', 'like', "%{$q}%");
            }

            return view('admin.kategori.index', [
                'kategoris' => $query->paginate(10)->withQueryString(),
            ]);
        }

        public function create(): View
        {
            return view('admin.kategori.create');
        }

        public function store(Request $request): RedirectResponse
        {
            $validated = $request->validate([
                'nama_kategori' => ['required', 'string', 'max:255', Rule::unique('kategori', 'nama_kategori')],
                'deskripsi' => ['nullable', 'string'],
                'status' => ['required', Rule::in(['active', 'inactive'])],
            ]);

            Kategori::create($validated);

            Cache::flush();

            return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
        }

        public function edit(Kategori $kategori): View
        {
            return view('admin.kategori.edit', [
                'kategori' => $kategori,
            ]);
        }

        public function update(Request $request, Kategori $kategori): RedirectResponse
        {
            $validated = $request->validate([
                'nama_kategori' => ['required', 'string', 'max:255', Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id)],
                'deskripsi' => ['nullable', 'string'],
                'status' => ['required', Rule::in(['active', 'inactive'])],
            ]);

            $kategori->update($validated);

            Cache::flush();

            return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
        }

        public function destroy(Kategori $kategori): RedirectResponse
        {
            // prevent deleting if used by fasilitas
            if ($kategori->fasilitas()->exists()) {
                return redirect()->route('admin.kategori.index')->withErrors([
                    'kategori' => 'Kategori ini masih dipakai oleh fasilitas, jadi tidak bisa dihapus.',
                ]);
            }

            $kategori->delete();

            Cache::flush();

            return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
        }
}
