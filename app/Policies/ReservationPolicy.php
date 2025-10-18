<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin();
    }

    public function pay(User $user, Reservation $reservation): bool
    {
        if ($reservation->user_id && $reservation->user_id === $user->id) {
            return !$reservation->isPaid();
        }

        return false;
    }
}
