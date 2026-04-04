<?php

use App\Http\Controllers\Booking as BookingController;
use App\Http\Controllers\Fasilitas as FasilitasController;
use App\Http\Controllers\ProfileController;
use App\Models\Booking as BookingModel;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $dashboardData = Cache::remember('admin.dashboard.summary', now()->addSeconds(60), function () {
        $totalBooking = BookingModel::count();
        $pendingBooking = BookingModel::where('status_booking', 'pending')->count();
        $confirmedBooking = BookingModel::where('status_booking', 'confirmed')->count();
        $fasilitasCount = Fasilitas::count();
        $activeKegiatanCount = Schema::hasColumn('kegiatan', 'status')
            ? Kegiatan::where('status', 'active')->count()
            : Kegiatan::count();
        $paymentCount = Pembayaran::count();
        $recentBookings = BookingModel::query()
            ->select([
                'id',
                'kode_booking',
                'kegiatan_id',
                'fasilitas_id',
                'tanggal_sewa',
                'tanggal_selesai',
                'status_booking',
                'total_harga',
            ])
            ->with([
                'kegiatan:id,nama_kegiatan',
                'fasilitas:id,nama_fasilitas',
            ])
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'totalBooking',
            'pendingBooking',
            'confirmedBooking',
            'fasilitasCount',
            'activeKegiatanCount',
            'paymentCount',
            'recentBookings'
        );
    });

    return view('admin.dashboard', [
        'stats' => [
            [
                'label' => 'Total Booking',
                'value' => $dashboardData['totalBooking'],
                'note' => 'Semua pengajuan',
                'tone' => 'bg-cyan-400/15 text-cyan-200 ring-cyan-400/20',
            ],
            [
                'label' => 'Booking Pending',
                'value' => $dashboardData['pendingBooking'],
                'note' => 'Menunggu verifikasi',
                'tone' => 'bg-amber-400/15 text-amber-200 ring-amber-400/20',
            ],
            [
                'label' => 'Booking Confirmed',
                'value' => $dashboardData['confirmedBooking'],
                'note' => 'Sudah disetujui',
                'tone' => 'bg-emerald-400/15 text-emerald-200 ring-emerald-400/20',
            ],
            [
                'label' => 'Fasilitas',
                'value' => $dashboardData['fasilitasCount'],
                'note' => 'Data tersedia',
                'tone' => 'bg-violet-400/15 text-violet-200 ring-violet-400/20',
            ],
            [
                'label' => 'Kegiatan Aktif',
                'value' => $dashboardData['activeKegiatanCount'],
                'note' => 'Siap dipilih',
                'tone' => 'bg-sky-400/15 text-sky-200 ring-sky-400/20',
            ],
            [
                'label' => 'Pembayaran',
                'value' => $dashboardData['paymentCount'],
                'note' => 'Tercatat di sistem',
                'tone' => 'bg-rose-400/15 text-rose-200 ring-rose-400/20',
            ],
        ],
        'recentBookings' => $dashboardData['recentBookings'],
    ]);
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/{id}', [FasilitasController::class, 'show'])->name('fasilitas.show');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
