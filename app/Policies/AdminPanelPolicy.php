<?php

namespace App\Policies;

use App\Models\User;

class AdminPanelPolicy
{
    /**
     * Determine if the user can access the admin panel.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
