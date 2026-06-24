<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $dashboardData = Cache::remember('admin.dashboard.summary', now()->addSeconds(60), function () {
            $totalBooking = Booking::count();
            $pendingBooking = Booking::where('status_booking', 'pending')->count();
            $confirmedBooking = Booking::where('status_booking', 'confirmed')->count();
            $fasilitasCount = Fasilitas::count();

            $recentBookings = Booking::query()
                ->with([
                    'user:id,nama',           
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
            ],
            'recentBookings' => $dashboardData['recentBookings'],
        ]);
    }
}