<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminOfNewBooking implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->get();
        
        // Notify each admin about the new booking
        foreach ($admins as $admin) {
            $admin->notify(new NewBookingNotification($event->booking));
        }
    }
}
