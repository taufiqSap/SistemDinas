<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas as FasilitasModel;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Fasilitas extends Controller
{
    public function index(Request $request)
    {
        $query = FasilitasModel::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'fasilitas.kategori_id')
            ->select([
                'fasilitas.id',
                'fasilitas.nama_fasilitas',
                'fasilitas.deskripsi',
                'fasilitas.kapasitas',
                'fasilitas.status_fasilitas',
                'fasilitas.gambar_fasilitas',
                'kategori.nama_kategori',
            ]);

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('fasilitas.nama_fasilitas', 'like', "%{$keyword}%")
                    ->orWhere('fasilitas.deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('kategori.nama_kategori', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori.nama_kategori', $request->kategori);
        }

        $sort = (string) $request->get('sort', 'name_asc');
        if ($sort === 'name_desc') {
            $query->orderByDesc('fasilitas.nama_fasilitas');
        } else {
            $query->orderBy('fasilitas.nama_fasilitas');
        }

        $fasilitas = $query->paginate(8)->withQueryString();

        $kategoriList = Cache::remember('fasilitas.index.kategoriList', now()->addMinutes(10), function () {
            return Kategori::query()->orderBy('nama_kategori')->pluck('nama_kategori');
        });

        return view('fasilitas.fasilitas', [
            'fasilitas' => $fasilitas,
            'kategoriList' => $kategoriList,
            'filters' => [
                'q' => (string) $request->get('q', ''),
                'kategori' => (string) $request->get('kategori', ''),
                'sort' => $sort,
            ],
        ]);
    }

    public function show(int $id)
    {
        $fasilitas = FasilitasModel::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'fasilitas.kategori_id')
            ->where('fasilitas.id', $id)
            ->select([
                'fasilitas.*',
                'kategori.nama_kategori',
            ])
            ->firstOrFail();

        // Bagian Kegiatan dihapus seluruhnya

        return view('fasilitas.detail', [
            'fasilitas' => $fasilitas,
            // Tidak mengirim variabel kegiatans
        ]);
    }
}