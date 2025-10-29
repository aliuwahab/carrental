<?php

namespace App\Listeners;

use App\Events\GuestAccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(GuestAccountCreated $event): void
    {
        $user = $event->user;
        $temporaryPassword = $event->temporaryPassword;
        
        // Send welcome email with temporary password
        Mail::send('emails.welcome-guest', [
            'user' => $user,
            'temporaryPassword' => $temporaryPassword,
            'resetUrl' => URL::temporarySignedRoute(
                'password.reset',
                now()->addHours(24),
                ['token' => app('auth.password.broker')->createToken($user)]
            )
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to Rental Ghana - Account Created');
        });
    }
}
