<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function show(User $user, User $modelUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $modelUser->id;
    }
}
