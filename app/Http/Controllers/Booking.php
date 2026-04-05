<?php

namespace App\Http\Controllers;

use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\Pembayaran;
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
                'latestPembayaran',
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

        $hargaSewaMap = Cache::remember('booking.create.hargaSewaMap', now()->addMinutes(10), function () {
            return DB::table('harga_sewa')
                ->select('fasilitas_id', 'tipe_sewa_id', 'harga')
                ->get()
                ->mapWithKeys(function ($row) {
                    return ["{$row->fasilitas_id}-{$row->tipe_sewa_id}" => (float) $row->harga];
                })
                ->all();
        });

        return view('booking.create', [
            'kegiatans' => $kegiatans,
            'fasilitass' => $fasilitass,
            'tipeSewas' => $tipeSewas,
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
            'bukti_pembayaran' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $pricing = $this->resolvePricing($validated);
        if ($pricing === null) {
            return redirect()->route('booking.create')
                ->withInput()
                ->withErrors([
                    'tipe_sewa_id' => 'Harga sewa untuk kombinasi fasilitas dan tipe sewa ini belum tersedia.',
                ]);
        }

        DB::transaction(function () use ($request, $validated, $pricing) {
            $booking = BookingModel::create([
                'kode_booking' => $this->generateBookingCode(),
                'user_id' => $request->user()->id,
                'fasilitas_id' => $validated['fasilitas_id'],
                'tipe_sewa_id' => $validated['tipe_sewa_id'],
                'kegiatan_id' => $validated['kegiatan_id'],
                'tanggal_sewa' => $validated['tanggal_sewa'],
                'tanggal_selesai' => $pricing['tanggal_selesai'],
                'durasi_hari' => $validated['durasi_hari'],
                'total_harga' => $pricing['total_harga'],
                'status_booking' => 'pending',
            ]);

            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            Pembayaran::create([
                'kode_pembayaran' => $this->generatePaymentCode(),
                'booking_id' => $booking->id,
                'metode_pembayaran' => 'qris',
                'bukti_pembayaran' => $buktiPath,
                'jumlah_pembayaran' => $pricing['total_harga'],
                'status_pembayaran' => 'pending',
                'alasan_penolakan' => '-',
                'tanggal_pembayaran' => now(),
            ]);
        });

        $request->session()->forget('booking_payment_draft');

        return redirect()->route('booking.create')->with('success', 'Booking berhasil dibuat.');
    }

    public function payment(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_id' => ['required', 'integer', 'exists:fasilitas,id'],
            'tipe_sewa_id' => ['required', 'integer', 'exists:tipe_sewa,id'],
            'kegiatan_id' => ['required', 'integer', 'exists:kegiatan,id'],
            'tanggal_sewa' => ['required', 'date'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
        ]);

        $pricing = $this->resolvePricing($validated);
        if ($pricing === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'tipe_sewa_id' => 'Harga sewa untuk kombinasi fasilitas dan tipe sewa ini belum tersedia.',
                ]);
        }

        $request->session()->put('booking_payment_draft', [
            'validated' => $validated,
            'pricing' => $pricing,
        ]);

        return redirect()->route('booking.payment.show');
    }

    public function showPayment(Request $request)
    {
        $draft = $request->session()->get('booking_payment_draft');

        if (! is_array($draft) || empty($draft['validated']) || empty($draft['pricing'])) {
            return redirect()->route('booking.create')->withErrors([
                'booking' => 'Data pembayaran tidak ditemukan. Silakan isi form booking kembali.',
            ]);
        }

        $validated = $draft['validated'];
        $pricing = $draft['pricing'];

        $fasilitas = Fasilitas::query()->select('id', 'nama_fasilitas')->findOrFail($validated['fasilitas_id']);
        $tipeSewa = TipeSewa::query()->select('id', 'nama_tipe')->findOrFail($validated['tipe_sewa_id']);
        $kegiatan = Kegiatan::query()->select('id', 'nama_kegiatan')->findOrFail($validated['kegiatan_id']);

        $qrisPayload = implode('|', [
            'SISTEM-DINAS',
            now()->format('YmdHis'),
            $request->user()->id,
            $validated['fasilitas_id'],
            $validated['tipe_sewa_id'],
            random_int(1000, 9999),
        ]);

        $qrisUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=420x420&margin=20&data=' . urlencode($qrisPayload);

        return view('booking.payment', [
            'validated' => $validated,
            'fasilitas' => $fasilitas,
            'tipeSewa' => $tipeSewa,
            'kegiatan' => $kegiatan,
            'hargaSatuan' => $pricing['harga_satuan'],
            'totalHarga' => $pricing['total_harga'],
            'tanggalSelesai' => $pricing['tanggal_selesai'],
            'qrisUrl' => $qrisUrl,
        ]);
    }

    private function resolvePricing(array $validated): ?array
    {
        $hargaSatuan = DB::table('harga_sewa')
            ->where('fasilitas_id', $validated['fasilitas_id'])
            ->where('tipe_sewa_id', $validated['tipe_sewa_id'])
            ->value('harga');

        if ($hargaSatuan === null) {
            return null;
        }

        $durasi = (int) $validated['durasi_hari'];
        $hargaSatuan = (float) $hargaSatuan;
        $totalHarga = $hargaSatuan * $durasi;
        $tanggalSewa = Carbon::parse($validated['tanggal_sewa']);
        $tanggalSelesai = (clone $tanggalSewa)->addDays($durasi - 1);

        return [
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
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

    private function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Pembayaran::where('kode_pembayaran', $code)->exists());

        return $code;
    }
}
