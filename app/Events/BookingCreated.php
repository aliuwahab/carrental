<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, SerializesModels;

    public $booking;
    public $isNewAccount;

    /**
     * Create a new event instance.
     */
    public function __construct(Booking $booking, bool $isNewAccount = false)
    {
        $this->booking = $booking;
        $this->isNewAccount = $isNewAccount;
    }
}
