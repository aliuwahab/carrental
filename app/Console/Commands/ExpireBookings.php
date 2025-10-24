<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings that have exceeded the 1-hour payment window';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->info("Expired {$count} booking(s) that exceeded the payment window.");
        } else {
            $this->info('No bookings to expire.');
        }

        return 0;
    }
}
