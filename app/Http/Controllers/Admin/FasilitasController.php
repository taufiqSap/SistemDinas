<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\HargaSewa;
use App\Models\Kategori;
use App\Models\TipeSewa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(): View
    {
        $hargaMulaiSubquery = DB::table('harga_sewa')
            ->select('fasilitas_id', DB::raw('MIN(harga) as harga_mulai'))
            ->groupBy('fasilitas_id');

        $fasilitas = Fasilitas::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'fasilitas.kategori_id')
            ->leftJoinSub($hargaMulaiSubquery, 'harga_mulai', function ($join) {
                $join->on('harga_mulai.fasilitas_id', '=', 'fasilitas.id');
            })
            ->select([
                'fasilitas.id',
                'fasilitas.nama_fasilitas',
                'fasilitas.status_fasilitas',
                'fasilitas.kapasitas',
                'kategori.nama_kategori',
                DB::raw('COALESCE(harga_mulai.harga_mulai, 0) as harga_mulai'),
            ])
            ->latest('fasilitas.created_at')
            ->paginate(10);

        return view('admin.fasilitas.index', [
            'fasilitas' => $fasilitas,
        ]);
    }

    public function create(): View
    {
        return view('admin.fasilitas.create', [
            'kategoriList' => Kategori::orderBy('nama_kategori')->get(),
            'tipeSewas' => TipeSewa::orderBy('nama_tipe')->get(),
            'statusOptions' => ['available', 'rented', 'maintenance'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori,id'],
            'nama_fasilitas' => ['required', 'string', 'max:255', Rule::unique('fasilitas', 'nama_fasilitas')],
            'deskripsi' => ['nullable', 'string'],
            'kapasitas' => ['required', 'string', 'max:255'],
            'spesifikasi' => ['required', 'string'],
            'status_fasilitas' => ['required', Rule::in(['available', 'rented', 'maintenance'])],
            'gambar_fasilitas' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'harga_per_tipe' => ['nullable', 'array'],
            'harga_per_tipe.*' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $gambarPath = $this->storeGambarFasilitas($request);

            $fasilitas = Fasilitas::create([
                'kategori_id' => $validated['kategori_id'],
                'nama_fasilitas' => $validated['nama_fasilitas'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'kapasitas' => $validated['kapasitas'],
                'spesifikasi' => $validated['spesifikasi'],
                'status_fasilitas' => $validated['status_fasilitas'],
                'gambar_fasilitas' => $gambarPath,
            ]);

            $this->syncHargaSewa($fasilitas->id, $validated['harga_per_tipe'] ?? []);
        });

        Cache::flush();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Fasilitas $fasilitas): View
    {
        $hargaPerTipe = HargaSewa::where('fasilitas_id', $fasilitas->id)
            ->pluck('harga', 'tipe_sewa_id')
            ->map(fn ($harga) => (float) $harga)
            ->all();

        return view('admin.fasilitas.edit', [
            'fasilitas' => $fasilitas,
            'kategoriList' => Kategori::orderBy('nama_kategori')->get(),
            'tipeSewas' => TipeSewa::orderBy('nama_tipe')->get(),
            'statusOptions' => ['available', 'rented', 'maintenance'],
            'hargaPerTipe' => $hargaPerTipe,
        ]);
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori,id'],
            'nama_fasilitas' => ['required', 'string', 'max:255', Rule::unique('fasilitas', 'nama_fasilitas')->ignore($fasilitas->id)],
            'deskripsi' => ['nullable', 'string'],
            'kapasitas' => ['required', 'string', 'max:255'],
            'spesifikasi' => ['required', 'string'],
            'status_fasilitas' => ['required', Rule::in(['available', 'rented', 'maintenance'])],
            'gambar_fasilitas' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'harga_per_tipe' => ['nullable', 'array'],
            'harga_per_tipe.*' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        DB::transaction(function () use ($request, $fasilitas, $validated) {
            $gambarPath = $fasilitas->gambar_fasilitas;

            if ($request->hasFile('gambar_fasilitas')) {
                $this->deleteStoredGambarFasilitas($fasilitas->gambar_fasilitas);
                $gambarPath = $this->storeGambarFasilitas($request);
            }

            $fasilitas->update([
                'kategori_id' => $validated['kategori_id'],
                'nama_fasilitas' => $validated['nama_fasilitas'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'kapasitas' => $validated['kapasitas'],
                'spesifikasi' => $validated['spesifikasi'],
                'status_fasilitas' => $validated['status_fasilitas'],
                'gambar_fasilitas' => $gambarPath,
            ]);

            $this->syncHargaSewa($fasilitas->id, $validated['harga_per_tipe'] ?? []);
        });

        Cache::flush();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->delete();

        Cache::flush();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }

    private function syncHargaSewa(int $fasilitasId, array $hargaPerTipe): void
    {
        DB::table('harga_sewa')->where('fasilitas_id', $fasilitasId)->delete();

        $rows = [];

        foreach ($hargaPerTipe as $tipeSewaId => $harga) {
            if ($harga === null || $harga === '') {
                continue;
            }

            $rows[] = [
                'fasilitas_id' => $fasilitasId,
                'tipe_sewa_id' => (int) $tipeSewaId,
                'harga' => (float) $harga,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($rows)) {
            DB::table('harga_sewa')->insert($rows);
        }
    }

    private function storeGambarFasilitas(Request $request): ?string
    {
        if (! $request->hasFile('gambar_fasilitas')) {
            return null;
        }

        return $request->file('gambar_fasilitas')->store('fasilitas', 'public');
    }

    private function deleteStoredGambarFasilitas(?string $storedPath): void
    {
        if (empty($storedPath)) {
            return;
        }

        $path = str_starts_with($storedPath, '/storage/')
            ? substr($storedPath, strlen('/storage/'))
            : (str_starts_with($storedPath, 'storage/') ? substr($storedPath, strlen('storage/')) : $storedPath);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}