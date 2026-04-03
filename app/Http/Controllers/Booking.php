<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\TipeSewa;
use Illuminate\Http\Request;

class Booking extends Controller
{
    public function create()
    {
        return view('booking.create', [
            'kegiatans' => Kegiatan::where('status', 'active')->orderBy('nama_kegiatan')->get(),
            'fasilitass' => Fasilitas::orderBy('nama_fasilitas')->get(),
            'tipeSewas' => TipeSewa::orderBy('nama_tipe')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => ['required', 'string', 'max:255', 'unique:booking,kode_booking'],
            'fasilitas_id' => ['required', 'integer', 'exists:fasilitas,id'],
            'tipe_sewa_id' => ['required', 'integer', 'exists:tipe_sewa,id'],
            'kegiatan_id' => ['required', 'integer', 'exists:kegiatan,id'],
            'tanggal_sewa' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_sewa'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
            'total_harga' => ['required', 'numeric', 'min:0'],
        ]);

        BookingModel::create($validated + [
            'user_id' => $request->user()->id,
            'status_booking' => 'pending',
        ]);

        return redirect()->route('booking.create')->with('success', 'Booking berhasil dibuat.');
    }
}
