<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\TipeSewa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Booking extends Controller
{
    public function history(Request $request)
    {
        $query = BookingModel::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'fasilitas:id,nama_fasilitas',
                'tipeSewa:id,nama_tipe',
                'kegiatan:id,nama_kegiatan',
            ]);

        if ($request->filled('status')) {
            $query->where('status_booking', $request->string('status'));
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('kode_booking', 'like', "%{$keyword}%")
                    ->orWhereHas('fasilitas', function ($relation) use ($keyword) {
                        $relation->where('nama_fasilitas', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('tipeSewa', function ($relation) use ($keyword) {
                        $relation->where('nama_tipe', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('kegiatan', function ($relation) use ($keyword) {
                        $relation->where('nama_kegiatan', 'like', "%{$keyword}%");
                    });
            });
        }

        $summary = (clone $query)
            ->selectRaw('status_booking, COUNT(*) as total')
            ->groupBy('status_booking')
            ->pluck('total', 'status_booking');

        $totalBookings = (clone $query)->count();

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('booking.history', [
            'bookings' => $bookings,
            'summary' => [
                'total' => $totalBookings,
                'pending' => (int) ($summary['pending'] ?? 0),
                'confirmed' => (int) ($summary['confirmed'] ?? 0),
                'cancelled' => (int) ($summary['cancelled'] ?? 0),
            ],
            'filters' => [
                'q' => (string) $request->get('q', ''),
                'status' => (string) $request->get('status', ''),
            ],
        ]);
    }

    public function create()
    {
        $hasKegiatanStatus = Schema::hasColumn('kegiatan', 'status');

        $kegiatans = Cache::remember('booking.create.kegiatans.' . (int) $hasKegiatanStatus, now()->addMinutes(10), function () use ($hasKegiatanStatus) {
            $kegiatanQuery = Kegiatan::query();

            if ($hasKegiatanStatus) {
                $kegiatanQuery->where('status', 'active');
            }

            return $kegiatanQuery->orderBy('nama_kegiatan')->get();
        });

        $fasilitass = Cache::remember('booking.create.fasilitass', now()->addMinutes(10), function () {
            return Fasilitas::orderBy('nama_fasilitas')->get();
        });

        $tipeSewas = Cache::remember('booking.create.tipeSewas', now()->addMinutes(10), function () {
            return TipeSewa::orderBy('nama_tipe')->get();
        });

        return view('booking.create', [
            'kegiatans' => $kegiatans,
            'fasilitass' => $fasilitass,
            'tipeSewas' => $tipeSewas,
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

        $bookingSummary = $this->resolveBookingSummary($validated);

        DB::transaction(function () use ($request, $validated, $bookingSummary) {
            BookingModel::create([
                'kode_booking' => $this->generateBookingCode(),
                'user_id' => $request->user()->id,
                'fasilitas_id' => $validated['fasilitas_id'],
                'tipe_sewa_id' => $validated['tipe_sewa_id'],
                'kegiatan_id' => $validated['kegiatan_id'],
                'tanggal_sewa' => $validated['tanggal_sewa'],
                'tanggal_selesai' => $bookingSummary['tanggal_selesai'],
                'durasi_hari' => $validated['durasi_hari'],
                // Semua penyewaan digratiskan sesuai kebijakan terbaru.
                'total_harga' => 0,
                'status_booking' => 'pending',
            ]);
        });

        return redirect()->route('booking.create')->with('success', 'Booking gratis berhasil dibuat.');
    }

    private function resolveBookingSummary(array $validated): array
    {
        $durasi = (int) $validated['durasi_hari'];
        $tanggalSewa = Carbon::parse($validated['tanggal_sewa']);
        $tanggalSelesai = (clone $tanggalSewa)->addDays($durasi - 1);

        return [
            'tanggal_selesai' => $tanggalSelesai->toDateString(),
        ];
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (BookingModel::where('kode_booking', $code)->exists());

        return $code;
    }

}
