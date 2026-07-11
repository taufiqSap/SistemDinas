<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $updatedCount = Booking::query()
            ->where('status_booking', 'approved')
            ->where('waktu_selesai', '<=', now())
            ->update(['status_booking' => 'completed']);

        if ($updatedCount > 0) {
            Cache::flush();
        }

        return $next($request);
    }
}
