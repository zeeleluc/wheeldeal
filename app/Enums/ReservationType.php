<?php

namespace App\Enums;

enum ReservationType: string
{
    case DRAFT = 'draft';
    case ABORTED = 'aborted';
    case CANCELLED = 'cancelled';
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';

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

    public function color(): string
    {
        return match ($this) {
            self::DRAFT, self::ABORTED => 'gray',
            self::CANCELLED => 'red',
            self::PENDING_PAYMENT => 'orange',
            self::PAID => 'green',
        };
    }

    public function circleClass(): string
    {
        return match ($this) {
            self::DRAFT, self::ABORTED => 'bg-gray-400',
            self::CANCELLED => 'bg-red-500',
            self::PENDING_PAYMENT => 'border-4 border-orange-400 border-t-orange-600 rounded-full animate-spin',
            self::PAID => 'bg-green-500',
        };
    }

    public function textClass(): string
    {
        return match ($this) {
            self::DRAFT, self::ABORTED => 'text-gray-600',
            self::CANCELLED => 'text-red-600',
            self::PENDING_PAYMENT => 'text-orange-600',
            self::PAID => 'text-green-600',
        };
    }
}
