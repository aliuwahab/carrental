<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\BookingCreated;
use App\Events\GuestAccountCreated;
use App\Listeners\SendBookingConfirmation;
use App\Listeners\SendWelcomeEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(
            BookingCreated::class,
            SendBookingConfirmation::class,
        );

        Event::listen(
            GuestAccountCreated::class,
            SendWelcomeEmail::class,
        );
    }
}
