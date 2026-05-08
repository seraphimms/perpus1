<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function manage(User $user): bool
    {
        return false;
    }
}
