<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function index(): View
    {
        return view('admin.kegiatan.index', [
            'kegiatans' => Kegiatan::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255', Rule::unique('kegiatan', 'nama_kegiatan')],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Kegiatan::create($validated);

        Cache::flush();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.edit', [
            'kegiatan' => $kegiatan,
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255', Rule::unique('kegiatan', 'nama_kegiatan')->ignore($kegiatan->id)],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kegiatan->update($validated);

        Cache::flush();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->bookings()->exists()) {
            return redirect()->route('admin.kegiatan.index')->withErrors([
                'kegiatan' => 'Kegiatan ini masih dipakai booking, jadi tidak bisa dihapus.',
            ]);
        }

        $kegiatan->delete();

        Cache::flush();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
