<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\CompleteBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private WhatsAppService $whatsApp) {}

    public function index(Request $request): View
    {
        $query = Booking::query()
            ->with([
                'user:id,nama,email',
                'fasilitas:id,nama_fasilitas',
            ])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_booking', $request->string('status'));
        }

        return view('admin.booking.index', [
            'bookings'      => $query->paginate(10)->withQueryString(),
            'filters'       => ['status' => (string) $request->get('status', '')],
            'statusOptions' => ['pending', 'approved', 'completed', 'rejected', 'cancelled'],
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'user:id,nama,email,alamat',
            'user.phone:user_id,no_hp',
            'fasilitas:id,nama_fasilitas,status_fasilitas',
        ]);

        return view('admin.booking.show', [
            'booking'       => $booking,
            'statusOptions' => ['pending', 'approved', 'rejected'],
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $rules = [
            'status_booking' => ['required', Rule::in(['pending', 'approved', 'rejected', 'cancelled'])],
        ];

        if ($request->input('status_booking') === 'rejected') {
            $rules['alasan_penolakan'] = ['required', 'string', 'min:5'];
        }

        if ($request->input('status_booking') === 'cancelled') {
            $rules['alasan_pembatalan'] = ['required', 'string', 'min:5'];
        }

        $validated = $request->validate($rules);

        // Cek konflik jadwal jika status akan di-approve
        if ($validated['status_booking'] === 'approved') {
            $conflict = Booking::where('fasilitas_id', $booking->fasilitas_id)
                ->whereIn('status_booking', ['pending', 'approved'])
                ->where('id', '!=', $booking->id)
                ->where(function ($query) use ($booking) {
                    $query->where('waktu_mulai', '<', $booking->waktu_selesai)
                          ->where('waktu_selesai', '>', $booking->waktu_mulai);
                })->exists();

            if ($conflict) {
                return back()->withErrors([
                    'status_booking' => 'Jadwal booking bentrok, tolak salah satu agar bisa konfirmasi booking.',
                ])->withInput();
            }
        }

        $data = ['status_booking' => $validated['status_booking']];

        if ($validated['status_booking'] === 'rejected') {
            $data['alasan_penolakan'] = $validated['alasan_penolakan'];
        }

        if ($validated['status_booking'] === 'cancelled') {
            $data['alasan_pembatalan'] = $validated['alasan_pembatalan'];
        }

        $booking->update($data);
        Cache::flush();

        if ($validated['status_booking'] === 'approved') {
            $this->dispatchCompletionJob($booking);
        }

        // Kirim notifikasi WhatsApp ke user
        $this->sendBookingNotification($booking, $validated);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();
        Cache::flush();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function create(): View
    {
        return view('admin.booking.create', [
            'fasilitasList' => Fasilitas::orderBy('nama_fasilitas')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'waktu_mulai'  => 'required|date|after_or_equal:now',
            'waktu_selesai'=> 'required|date|after:waktu_mulai',
            'kegiatan'     => 'required|string|min:5',
            'dokumen_pdf'  => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $conflict = Booking::where('fasilitas_id', $validated['fasilitas_id'])
            ->where('status_booking', 'approved')
            ->where(function ($query) use ($validated) {
                $query->where('waktu_mulai', '<', $validated['waktu_selesai'])
                      ->where('waktu_selesai', '>', $validated['waktu_mulai']);
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['waktu_mulai' => 'Fasilitas sudah di-approve pada rentang waktu tersebut.'])
                ->withInput();
        }

        $pdfPath = null;
        if ($request->hasFile('dokumen_pdf')) {
            $pdfPath = $request->file('dokumen_pdf')->store('dokumen_booking', 'public');
        }

        $booking = Booking::create([
            'kode_booking'   => $this->generateBookingCode(),
            'user_id'        => auth()->id(),
            'fasilitas_id'   => $validated['fasilitas_id'],
            'waktu_mulai'    => $validated['waktu_mulai'],
            'waktu_selesai'  => $validated['waktu_selesai'],
            'kegiatan'       => $validated['kegiatan'],
            'dokumen_pdf'    => $pdfPath,
            'status_booking' => 'approved',
        ]);

        Cache::flush();

        $this->dispatchCompletionJob($booking);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', "Booking berhasil dibuat dengan kode {$booking->kode_booking} (langsung approved).");
    }


    /**
     * Kirim notifikasi WhatsApp setelah status booking diperbarui.
     * Pengiriman hanya dilakukan sekali (1x) per perubahan status.
     */
    private function sendBookingNotification(Booking $booking, array $validated): void
    {
        // Pastikan relasi user & phone sudah ter-load
        $booking->loadMissing(['user.phone', 'fasilitas:id,nama_fasilitas']);

        $noHp = $booking->user?->phone?->no_hp;

        if (empty($noHp)) {
            Log::warning('[WhatsApp] Booking notification skipped — no phone number', [
                'booking_id' => $booking->id,
            ]);
            return;
        }

        $namaUser      = $booking->user->nama;
        $namaFasilitas = $booking->fasilitas->nama_fasilitas ?? '-';
        $status        = $validated['status_booking'];

        $message = match ($status) {
            'approved' => "Halo {$namaUser}, fasilitas *{$namaFasilitas}* yang Anda pinjam sudah dikonfirmasi oleh admin. Terima kasih!",
            'rejected' => "Halo {$namaUser}, permohonan booking fasilitas *{$namaFasilitas}* Anda ditolak.\n\nAlasan: " . ($validated['alasan_penolakan'] ?? '-'),
            default    => null,
        };

        if ($message === null) {
            return; // status lain (pending, cancelled) tidak dikirim notifikasi
        }

        $this->whatsApp->send($noHp, $message);
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK' . now()->format('Ymd') . '' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Booking::where('kode_booking', $code)->exists());

        return $code;
    }

    private function dispatchCompletionJob(Booking $booking): void
    {
        if ($booking->status_booking !== 'approved') {
            return;
        }

        if (now()->lt($booking->waktu_selesai)) {
            CompleteBooking::dispatch($booking->id)->delay($booking->waktu_selesai);

            return;
        }

        CompleteBooking::dispatch($booking->id);
    }
}