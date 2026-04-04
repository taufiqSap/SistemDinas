<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\TipeSewa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Booking extends Controller
{
    public function create()
    {
        $kegiatanQuery = Kegiatan::query();

        if (Schema::hasColumn('kegiatan', 'status')) {
            $kegiatanQuery->where('status', 'active');
        }

        $hargaSewaMap = DB::table('harga_sewa')
            ->select('fasilitas_id', 'tipe_sewa_id', 'harga')
            ->get()
            ->mapWithKeys(function ($row) {
                return ["{$row->fasilitas_id}-{$row->tipe_sewa_id}" => (float) $row->harga];
            })
            ->all();

        return view('booking.create', [
            'kegiatans' => $kegiatanQuery->orderBy('nama_kegiatan')->get(),
            'fasilitass' => Fasilitas::orderBy('nama_fasilitas')->get(),
            'tipeSewas' => TipeSewa::orderBy('nama_tipe')->get(),
            'hargaSewaMap' => $hargaSewaMap,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_id' => ['required', 'integer', 'exists:fasilitas,id'],
            'tipe_sewa_id' => ['required', 'integer', 'exists:tipe_sewa,id'],
            'kegiatan_id' => ['required', 'integer', 'exists:kegiatan,id'],
            'tanggal_sewa' => ['required', 'date'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
        ]);

        $hargaSatuan = DB::table('harga_sewa')
            ->where('fasilitas_id', $validated['fasilitas_id'])
            ->where('tipe_sewa_id', $validated['tipe_sewa_id'])
            ->value('harga');

        if ($hargaSatuan === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'tipe_sewa_id' => 'Harga sewa untuk kombinasi fasilitas dan tipe sewa ini belum tersedia.',
                ]);
        }

        $totalHarga = (float) $hargaSatuan * (int) $validated['durasi_hari'];

        $tanggalSewa = Carbon::parse($validated['tanggal_sewa']);
        $tanggalSelesai = (clone $tanggalSewa)->addDays(((int) $validated['durasi_hari']) - 1);

        BookingModel::create($validated + [
            'kode_booking' => $this->generateBookingCode(),
            'tanggal_selesai' => $tanggalSelesai->toDateString(),
            'total_harga' => $totalHarga,
            'user_id' => $request->user()->id,
            'status_booking' => 'pending',
        ]);

        return redirect()->route('booking.create')->with('success', 'Booking berhasil dibuat.');
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (BookingModel::where('kode_booking', $code)->exists());

        return $code;
    }
}
