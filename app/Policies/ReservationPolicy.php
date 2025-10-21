<?php

namespace App\Policies;

use App\Enums\PaymentStatus;
use App\Enums\ReservationType;
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

    public function create(?User $user): bool
    {
        if (!$user) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return !$user->hasRecentReservation();
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
        if (!$reservation->user->is($user)) {
            return false;
        }

        return match ($reservation->status) {
            ReservationType::PENDING_PAYMENT => ! $reservation->payments()
                ->where('status', PaymentStatus::PENDING)
                ->exists(),
            ReservationType::ABORTED, ReservationType::PAID => false,
            default => true,
        };
    }
}
