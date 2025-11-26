<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\GuestAccountCreated;
use App\Listeners\SendBookingConfirmation;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\NotifyAdminOfNewBooking;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        BookingCreated::class => [
            SendBookingConfirmation::class,
            NotifyAdminOfNewBooking::class,
        ],
        
        GuestAccountCreated::class => [
            SendWelcomeEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
