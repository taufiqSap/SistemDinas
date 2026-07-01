<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $bookingId)
    {
    }

    public function handle(): void
    {
        $booking = Booking::query()->find($this->bookingId);

        if (! $booking) {
            return;
        }

        if ($booking->status_booking !== 'approved') {
            return;
        }

        if (now()->lt($booking->waktu_selesai)) {
            return;
        }

        $booking->forceFill([
            'status_booking' => 'completed',
        ])->save();
    }
}