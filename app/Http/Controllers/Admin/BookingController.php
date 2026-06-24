<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\User;   
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::query()
            ->with([
                'user:id,nama,email',
                'fasilitas:id,nama_fasilitas',
                // Hapus 'kegiatan' karena tidak ada relasi, kegiatan sekarang kolom teks
            ])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_booking', $request->string('status'));
        }

        return view('admin.booking.index', [
            'bookings' => $query->paginate(10)->withQueryString(),
            'filters' => [
                'status' => (string) $request->get('status', ''),
            ],
            'statusOptions' => ['pending', 'confirmed', 'cancelled'],
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'user:id,nama,email,no_hp,alamat',
            'fasilitas:id,nama_fasilitas,status_fasilitas',
            // Hapus 'kegiatan'
        ]);

        return view('admin.booking.show', [
            'booking' => $booking,
            'statusOptions' => ['pending', 'confirmed', 'cancelled'],
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
{
    $rules = [
        'status_booking' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
    ];

    if ($request->input('status_booking') === 'cancelled') {
        $rules['alasan_pembatalan'] = ['required', 'string', 'min:5']; 
    }

    $validated = $request->validate($rules);

    $data = ['status_booking' => $validated['status_booking']];

    if ($validated['status_booking'] === 'cancelled') {
        $data['alasan_pembatalan'] = $validated['alasan_pembatalan']; 
    }

    $booking->update($data);

    Cache::flush();

    return redirect()->route('admin.bookings.show', $booking)
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
    $fasilitasList = Fasilitas::orderBy('nama_fasilitas')->get();

    return view('admin.booking.create', [
        'fasilitasList' => $fasilitasList,
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

    // Cek konflik booking (confirmed)
    $conflict = Booking::where('fasilitas_id', $validated['fasilitas_id'])
        ->where('status_booking', 'confirmed')
        ->where(function ($query) use ($validated) {
            $query->where('waktu_mulai', '<', $validated['waktu_selesai'])
                  ->where('waktu_selesai', '>', $validated['waktu_mulai']);
        })->exists();

    if ($conflict) {
        return back()
            ->withErrors(['waktu_mulai' => 'Fasilitas sudah dibooking pada rentang waktu tersebut.'])
            ->withInput();
    }

    // Upload PDF
    $pdfPath = null;
    if ($request->hasFile('dokumen_pdf')) {
        $pdfPath = $request->file('dokumen_pdf')->store('dokumen_booking', 'public');
    }

    // Buat booking dengan user_id = admin yang login
    $booking = Booking::create([
        'kode_booking'   => $this->generateBookingCode(),
        'user_id'        => auth()->id(), // otomatis admin
        'fasilitas_id'   => $validated['fasilitas_id'],
        'waktu_mulai'    => $validated['waktu_mulai'],
        'waktu_selesai'  => $validated['waktu_selesai'],
        'kegiatan'       => $validated['kegiatan'],
        'dokumen_pdf'    => $pdfPath,
        'status_booking' => 'confirmed',
    ]);

    Cache::flush();

    return redirect()
        ->route('admin.bookings.index')
        ->with('success', "Booking berhasil dibuat dengan kode {$booking->kode_booking} (langsung confirmed).");
}

    /**
     * Generate kode booking unik.
     */
   private function generateBookingCode(): string
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    do {
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
    } while (Booking::where('kode_booking', $code)->exists());

    return $code;
}
}

