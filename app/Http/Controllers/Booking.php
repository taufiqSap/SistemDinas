<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Booking extends Controller
{
    public function show(string $date): JsonResponse
    {
        try {
            $selectedDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'Format tanggal tidak valid.',
            ], 422);
        }

        $bookings = BookingModel::query()
            ->where('status_booking', '!=', 'cancelled')
            ->whereDate('tanggal_sewa', '<=', $selectedDate->toDateString())
            ->whereDate('tanggal_selesai', '>=', $selectedDate->toDateString())
            ->with([
                'user:id,nama',
                'fasilitas:id,nama_fasilitas',
                'kegiatan:id,nama_kegiatan',
            ])
            ->orderBy('tanggal_sewa')
            ->get()
            ->map(function (BookingModel $booking) {
                return [
                    'kode_booking' => $booking->kode_booking,
                    'user_id' => $booking->user_id,
                    'nama_pemesan' => $booking->user?->nama ?? '-',
                    'agenda' => $booking->kegiatan?->nama_kegiatan ?? '-',
                    'fasilitas' => $booking->fasilitas?->nama_fasilitas ?? '-',
                    'tanggal_sewa' => Carbon::parse($booking->tanggal_sewa)->translatedFormat('d F Y'),
                    'tanggal_selesai' => Carbon::parse($booking->tanggal_selesai)->translatedFormat('d F Y'),
                    'durasi_hari' => (int) $booking->durasi_hari,
                    'status_booking' => $booking->status_booking,
                ];
            })
            ->values();

        return response()->json([
            'tanggal' => $selectedDate->toDateString(),
            'tanggal_label' => $selectedDate->translatedFormat('d F Y'),
            'bookings' => $bookings,
        ]);
    }

    public function history(Request $request)
    {
        $query = BookingModel::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'fasilitas:id,nama_fasilitas',
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

        return view('booking.create', [
            'kegiatans' => $kegiatans,
            'fasilitass' => $fasilitass,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_id' => ['required', 'integer', 'exists:fasilitas,id'],
            'kegiatan_id' => ['required', 'integer', 'exists:kegiatan,id'],
            'tanggal_sewa' => ['required', 'date'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
        ]);

        $bookingSummary = $this->resolveBookingSummary($validated);

        // Validate conflicts: do not allow creating a booking that overlaps
        // with existing bookings from OTHER users for the same facility.
        $tanggalSewa = Carbon::parse($validated['tanggal_sewa'])->startOfDay();
        $tanggalSelesai = Carbon::parse($bookingSummary['tanggal_selesai'])->startOfDay();

        $conflictExists = BookingModel::query()
            ->where('status_booking', '!=', 'cancelled')
            ->where('fasilitas_id', $validated['fasilitas_id'])
            ->where('user_id', '!=', $request->user()->id)
            ->whereDate('tanggal_sewa', '<=', $tanggalSelesai->toDateString())
            ->whereDate('tanggal_selesai', '>=', $tanggalSewa->toDateString())
            ->exists();

        if ($conflictExists) {
            return redirect()->back()
                ->withErrors(['tanggal_sewa' => 'Tanggal yang dipilih bentrok dengan booking pengguna lain.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $validated, $bookingSummary) {
            BookingModel::create([
                'kode_booking' => $this->generateBookingCode(),
                'user_id' => $request->user()->id,
                'fasilitas_id' => $validated['fasilitas_id'],
                'kegiatan_id' => $validated['kegiatan_id'],
                'tanggal_sewa' => $validated['tanggal_sewa'],
                'tanggal_selesai' => $bookingSummary['tanggal_selesai'],
                'durasi_hari' => $validated['durasi_hari'],
                'status_booking' => 'pending',
            ]);
        });

        return redirect()->back()->with('success', 'Booking gratis berhasil dibuat.');
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
