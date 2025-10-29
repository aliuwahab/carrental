<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestAccountCreated
{
    use Dispatchable, SerializesModels;

    public $user;
    public $temporaryPassword;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }
}
