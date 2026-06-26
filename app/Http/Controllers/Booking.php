<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage; 

class Booking extends Controller
{
    public function show(Request $request, string $date): JsonResponse
{
    try {
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
    } catch (\Throwable $exception) {
        return response()->json([
            'message' => 'Format tanggal tidak valid.',
        ], 422);
    }

    $bookingsQuery = BookingModel::query()
        ->where('status_booking', '!=', 'cancelled')
        ->where(function($query) use ($selectedDate) {
            $query->whereDate('waktu_mulai', '<=', $selectedDate->toDateString())
                  ->whereDate('waktu_selesai', '>=', $selectedDate->toDateString());
        });

    if ($request->filled('fasilitas_id')) {
        $bookingsQuery->where('fasilitas_id', (int) $request->input('fasilitas_id'));
    }

    // Cek apakah kolom 'nama_lembaga' ada di tabel users
    $hasLembaga = Schema::hasColumn('users', 'nama_lembaga');

    // Tentukan field yang akan di-select dari tabel users
    $userFields = ['id', 'nama'];
    if ($hasLembaga) {
        $userFields[] = 'nama_lembaga';
    }

    $bookings = $bookingsQuery
        ->with([
            'user' => function ($query) use ($userFields) {
                $query->select($userFields);
            },
            'fasilitas:id,nama_fasilitas',
        ])
        ->orderBy('waktu_mulai')
        ->get()
        ->map(function (BookingModel $booking) use ($hasLembaga) {
            return [
                'kode_booking'   => $booking->kode_booking,
                'user_id'        => $booking->user_id,
                'nama_pemesan'   => $booking->user?->nama ?? '-',
                'lembaga'        => $hasLembaga ? ($booking->user?->nama_lembaga ?? null) : null,
                'kegiatan'       => $booking->kegiatan ?? '-',
                'fasilitas'      => $booking->fasilitas?->nama_fasilitas ?? '-',
                'waktu_mulai'    => Carbon::parse($booking->waktu_mulai)->translatedFormat('d F Y H:i'),
                'waktu_selesai'  => Carbon::parse($booking->waktu_selesai)->translatedFormat('d F Y H:i'),
                'status_booking' => $booking->status_booking,
                'dokumen_pdf'    => $booking->dokumen_pdf ? asset('storage/' . $booking->dokumen_pdf) : null,
            ];
        })
        ->values();

    return response()->json([
        'tanggal'       => $selectedDate->toDateString(),
        'tanggal_label' => $selectedDate->translatedFormat('d F Y'),
        'bookings'      => $bookings,
    ]);
}

   public function history(Request $request)
{
    // Query dasar tanpa ORDER BY
    $baseQuery = BookingModel::query()
        ->where('user_id', $request->user()->id)
        ->with(['fasilitas:id,nama_fasilitas']);

    // Filter status
    if ($request->filled('status')) {
        $baseQuery->where('status_booking', $request->string('status'));
    }

    // Pencarian teks
    if ($request->filled('q')) {
        $keyword = trim((string) $request->q);
        $baseQuery->where(function ($builder) use ($keyword) {
            $builder->where('kode_booking', 'like', "%{$keyword}%")
                ->orWhere('kegiatan', 'like', "%{$keyword}%")
                ->orWhereHas('fasilitas', function ($relation) use ($keyword) {
                    $relation->where('nama_fasilitas', 'like', "%{$keyword}%");
                });
        });
    }

    // Ringkasan status (clone tanpa ORDER BY)
    $summary = (clone $baseQuery)
        ->selectRaw('status_booking, COUNT(*) as total')
        ->groupBy('status_booking')
        ->pluck('total', 'status_booking');

    $totalBookings = (clone $baseQuery)->count();

    // Data booking dengan urutan dan paginasi
    $bookings = (clone $baseQuery)
        ->orderBy('created_at', 'desc')
        ->paginate(15)
        ->appends($request->query());

    return view('booking.history', [
        'bookings' => $bookings,
        'summary' => [
            'total'      => $totalBookings,
            'pending'    => (int) ($summary['pending'] ?? 0),
            'approved'   => (int) ($summary['approved'] ?? 0),
            'rejected'   => (int) ($summary['rejected'] ?? 0),
            'cancelled'  => (int) ($summary['cancelled'] ?? 0),
        ],
        'filters' => [
            'q'      => (string) $request->get('q', ''),
            'status' => (string) $request->get('status', ''),
        ],
    ]);
}

    public function create()
    {
        $fasilitass = Cache::remember('booking.create.fasilitass', now()->addMinutes(10), function () {
            return Fasilitas::orderBy('nama_fasilitas')->get();
        });

        // Relasi master Kegiatan dihapus dari form karena kegiatan sekarang diisi bebas via teks input wajib
        return view('booking.create', [
            'fasilitass' => $fasilitass,
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi input baru (Menerima input datetime dari form html / flatpickr)
        $validated = $request->validate([
            'fasilitas_id' => ['required', 'integer', 'exists:fasilitas,id'],
            'waktu_mulai' => ['required', 'date', 'after_or_equal:now'],
            'waktu_selesai' => ['required', 'date', 'after:waktu_mulai'],
            'kegiatan' => ['required', 'string', 'min:5'], // Wajib diisi berupa teks
            'dokumen_pdf' => ['', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:2048'], // Wajib upload file PDF maks 2MB
        ]);

        $waktuMulai = Carbon::parse($validated['waktu_mulai']);
        $waktuSelesai = Carbon::parse($validated['waktu_selesai']);

        // 2. LOGIKA ANTI-BENTROK BARU (Per Jam & Lintas Hari)
        // Mengecek apakah ada booking terkonfirmasi lain yang rentang waktunya saling bersinggungan
        // Cek konflik (hanya terhadap booking yang sudah approved)
        $conflictExists = BookingModel::query()
            ->where('status_booking', 'approved') // ← ubah dari 'confirmed'
            ->where('fasilitas_id', $validated['fasilitas_id'])
            ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                $query->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
            })
             ->exists();

        if ($conflictExists) {
            return redirect()->back()
                ->withErrors(['waktu_mulai' => 'Fasilitas sudah dibooking pada rentang waktu/jam tersebut.'])
                ->withInput();
        }

        // 3. Proses upload dokumen PDF ke folder public
        $pdfPath = null;
        if ($request->hasFile('dokumen_pdf')) {
            $pdfPath = $request->file('dokumen_pdf')->store('dokumen_booking', 'public');
        }

        // 4. Proses simpan ke database aman menggunakan Transaction
        DB::transaction(function () use ($request, $validated, $waktuMulai, $waktuSelesai, $pdfPath) {
            BookingModel::create([
                'kode_booking' => $this->generateBookingCode(),
                'user_id' => $request->user()->id,
                'fasilitas_id' => $validated['fasilitas_id'],
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'kegiatan' => $validated['kegiatan'],
                'dokumen_pdf' => $pdfPath,
                'status_booking' => 'pending',
            ]);
        });

        return redirect()->back()->with('success', 'Booking berhasil diajukan dan menunggu persetujuan.');
    }
    // Di dalam App\Http\Controllers\Booking

public function cancel(Request $request, $id)
{
    $booking = BookingModel::where('user_id', $request->user()->id)
                ->where('id', $id)
                ->firstOrFail();

    // Hanya boleh dibatalkan jika status masih pending atau approved
    if (!in_array($booking->status_booking, ['pending', 'approved'])) {
        return back()->withErrors(['error' => 'Booking ini tidak dapat dibatalkan.']);
    }

    $validated = $request->validate([
        'alasan_pembatalan' => ['required', 'string', 'min:5'],
    ]);

    $booking->update([
        'status_booking' => 'cancelled',
        'alasan_pembatalan' => $validated['alasan_pembatalan'],
    ]);

    // Hapus cache jika perlu
    Cache::flush();

    return redirect()->route('booking.history')
        ->with('success', 'Booking berhasil dibatalkan.');
}

   private function generateBookingCode(): string
    {
        do {
            $code = 'BK' . now()->format('Ymd') . '' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (BookingModel::where('kode_booking', $code)->exists());

        return $code;
    }
}