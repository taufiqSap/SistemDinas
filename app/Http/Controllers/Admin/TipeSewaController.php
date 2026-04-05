<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipeSewa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TipeSewaController extends Controller
{
    public function index(): View
    {
        return view('admin.tipe-sewa.index', [
            'tipeSewas' => TipeSewa::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.tipe-sewa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tipe' => ['required', 'string', 'max:255', Rule::unique('tipe_sewa', 'nama_tipe')],
            'deskripsi' => ['nullable', 'string'],
        ]);

        TipeSewa::create($validated);

        Cache::flush();

        return redirect()->route('admin.tipe-sewa.index')->with('success', 'Tipe sewa berhasil ditambahkan.');
    }

    public function edit(TipeSewa $tipe_sewa): View
    {
        return view('admin.tipe-sewa.edit', [
            'tipeSewa' => $tipe_sewa,
        ]);
    }

    public function update(Request $request, TipeSewa $tipe_sewa): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tipe' => ['required', 'string', 'max:255', Rule::unique('tipe_sewa', 'nama_tipe')->ignore($tipe_sewa->id)],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $tipe_sewa->update($validated);

        Cache::flush();

        return redirect()->route('admin.tipe-sewa.index')->with('success', 'Tipe sewa berhasil diperbarui.');
    }

    public function destroy(TipeSewa $tipe_sewa): RedirectResponse
    {
        $tipe_sewa->delete();

        Cache::flush();

        return redirect()->route('admin.tipe-sewa.index')->with('success', 'Tipe sewa berhasil dihapus.');
    }
}