<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Notifications\BookingPaymentInstructions;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of seconds to wait before processing the job.
     *
     * @var int
     */
    public $delay = 300; // 5 minutes delay

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        // Send payment instructions to the customer
        $event->booking->user->notify(
            new BookingPaymentInstructions(
                $event->booking,
                $event->isNewAccount
            )
        );
    }
}
