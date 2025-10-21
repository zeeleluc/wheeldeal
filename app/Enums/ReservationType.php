<?php

namespace App\Enums;

enum ReservationType: string
{
    case DRAFT = 'draft';
    case ABORTED = 'aborted';
    case CANCELLED = 'cancelled';
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function title(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ABORTED => 'Aborted',
            self::CANCELLED => 'Cancelled',
            self::PENDING_PAYMENT => 'Pending Payment',
            self::PAID => 'Paid',
        };
    }
}
