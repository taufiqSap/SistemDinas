<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
                'tipeSewa:id,nama_tipe',
                'kegiatan:id,nama_kegiatan',
                'pembayaran:id,booking_id,bukti_pembayaran',
            ])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_booking', $request->string('status'));
        }

        if ($request->filled('bukti')) {
            if ($request->string('bukti') === 'ada') {
                $query->whereHas('pembayaran', function ($relation) {
                    $relation->whereNotNull('bukti_pembayaran')->where('bukti_pembayaran', '!=', '');
                });
            } elseif ($request->string('bukti') === 'belum') {
                $query->whereDoesntHave('pembayaran', function ($relation) {
                    $relation->whereNotNull('bukti_pembayaran')->where('bukti_pembayaran', '!=', '');
                });
            }
        }

        return view('admin.booking.index', [
            'bookings' => $query->paginate(10)->withQueryString(),
            'filters' => [
                'status' => (string) $request->get('status', ''),
                'bukti' => (string) $request->get('bukti', ''),
            ],
            'statusOptions' => ['pending', 'confirmed', 'cancelled'],
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'user:id,nama,email,no_hp,alamat',
            'fasilitas:id,nama_fasilitas,status_fasilitas',
            'tipeSewa:id,nama_tipe',
            'kegiatan:id,nama_kegiatan',
            'pembayaran:id,booking_id,bukti_pembayaran,status_pembayaran,jumlah_pembayaran,kode_pembayaran,metode_pembayaran,tanggal_pembayaran,alasan_penolakan',
        ]);

        return view('admin.booking.show', [
            'booking' => $booking,
            'statusOptions' => ['pending', 'confirmed', 'cancelled'],
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status_booking' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
        ]);

        $booking->update([
            'status_booking' => $validated['status_booking'],
            'deadline_pembayaran' => $validated['status_booking'] === 'confirmed' && ! $booking->deadline_pembayaran
                ? now()->addDays(3)
                : $booking->deadline_pembayaran,
        ]);

        Cache::flush();

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        Cache::flush();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}