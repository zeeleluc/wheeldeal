<?php

namespace App\Helpers;

use App\Models\Reservation;
use App\Enums\PaymentStatus;
use App\Enums\ReservationType;

class ReservationStatusHelper
{
    public static function resolve(Reservation $reservation): void
    {
        $latestPayment = $reservation->latestPayment();

        if (!$latestPayment) {
            return;
        }

        $status = match ($latestPayment->status) {
            PaymentStatus::SUCCESS => ReservationType::PAID,
            PaymentStatus::PENDING => ReservationType::PENDING_PAYMENT,
            PaymentStatus::CANCELLED, PaymentStatus::ISSUED => ReservationType::CANCELLED,
            PaymentStatus::REJECTED, PaymentStatus::FAILED => ReservationType::ABORTED,
        };

        if ($reservation->status !== $status) {
            $reservation->setStatus($status);
        }
    }
}
