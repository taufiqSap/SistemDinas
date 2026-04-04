<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas as FasilitasModel;
use App\Models\Kegiatan;
use App\Models\TipeSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Fasilitas extends Controller
{
    public function index(Request $request)
    {
        $hargaMulaiSubquery = DB::table('harga_sewa')
            ->select('fasilitas_id', DB::raw('MIN(harga) as harga_mulai'))
            ->groupBy('fasilitas_id');

        $query = FasilitasModel::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'fasilitas.kategori_id')
            ->leftJoinSub($hargaMulaiSubquery, 'harga_mulai', function ($join) {
                $join->on('harga_mulai.fasilitas_id', '=', 'fasilitas.id');
            })
            ->select([
                'fasilitas.id',
                'fasilitas.nama_fasilitas',
                'fasilitas.deskripsi',
                'fasilitas.kapasitas',
                'fasilitas.status_fasilitas',
                'fasilitas.gambar_fasilitas',
                'kategori.nama_kategori',
                DB::raw('COALESCE(harga_mulai.harga_mulai, 0) as harga_mulai'),
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

        $sort = $request->get('sort', 'name_asc');
        if ($sort === 'price_asc') {
            $query->orderBy('harga_mulai')->orderBy('fasilitas.nama_fasilitas');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('harga_mulai')->orderBy('fasilitas.nama_fasilitas');
        } else {
            $query->orderBy('fasilitas.nama_fasilitas');
        }

        $fasilitas = $query->paginate(8)->withQueryString();

        $kategoriList = DB::table('kategori')
            ->where('status', 'active')
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori');

        return view('fasilitas.fasilitas', [
            'fasilitas' => $fasilitas,
            'kategoriList' => $kategoriList,
            'filters' => [
                'q' => (string) $request->get('q', ''),
                'kategori' => (string) $request->get('kategori', ''),
                'sort' => (string) $sort,
            ],
        ]);
    }

    public function show(int $id)
    {
        $hargaMulaiSubquery = DB::table('harga_sewa')
            ->select('fasilitas_id', DB::raw('MIN(harga) as harga_mulai'))
            ->groupBy('fasilitas_id');

        $fasilitas = FasilitasModel::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'fasilitas.kategori_id')
            ->leftJoinSub($hargaMulaiSubquery, 'harga_mulai', function ($join) {
                $join->on('harga_mulai.fasilitas_id', '=', 'fasilitas.id');
            })
            ->where('fasilitas.id', $id)
            ->select([
                'fasilitas.*',
                'kategori.nama_kategori',
                DB::raw('COALESCE(harga_mulai.harga_mulai, 0) as harga_mulai'),
            ])
            ->firstOrFail();

        $kegiatanQuery = Kegiatan::query();
        if (Schema::hasColumn('kegiatan', 'status')) {
            $kegiatanQuery->where('status', 'active');
        }

        $hargaPerTipe = DB::table('harga_sewa')
            ->where('fasilitas_id', $fasilitas->id)
            ->pluck('harga', 'tipe_sewa_id')
            ->map(fn ($harga) => (float) $harga)
            ->all();

        return view('fasilitas.detail', [
            'fasilitas' => $fasilitas,
            'tipeSewas' => TipeSewa::orderBy('nama_tipe')->get(),
            'kegiatans' => $kegiatanQuery->orderBy('nama_kegiatan')->get(),
            'hargaPerTipe' => $hargaPerTipe,
        ]);
    }
}
