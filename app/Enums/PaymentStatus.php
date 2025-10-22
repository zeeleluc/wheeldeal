<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case FAILED = 'failed';
    case ISSUED = 'issued';
    case CANCELLED = 'cancelled';

    public function title(): string
    {
        return match ($this) {
            self::SUCCESS => 'Success',
            self::PENDING => 'Pending',
            self::REJECTED => 'Rejected',
            self::FAILED => 'Failed',
            self::ISSUED => 'Issued',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUCCESS => 'green',
            self::PENDING => 'orange',
            self::REJECTED, self::FAILED => 'red',
            self::ISSUED => 'blue',
            self::CANCELLED => 'gray',
        };
    }

    public function circleClass(): string
    {
        return match ($this) {
            self::SUCCESS => 'bg-green-500 rounded-full',
            self::PENDING => 'border-4 border-orange-400 border-t-orange-600 animate-spin rounded-full',
            self::REJECTED, self::FAILED => 'bg-red-500 rounded-full',
            self::ISSUED => 'bg-blue-500 rounded-full',
            self::CANCELLED => 'bg-gray-400 rounded-full',
        };
    }

    public function textClass(): string
    {
        return match ($this) {
            self::SUCCESS => 'text-green-600',
            self::PENDING => 'text-orange-600',
            self::REJECTED, self::FAILED => 'text-red-600',
            self::ISSUED => 'text-blue-600',
            self::CANCELLED => 'text-gray-600',
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::SUCCESS => 'Your payment was successful.',
            self::PENDING => 'Your payment is pending.',
            self::REJECTED, self::FAILED => 'Your payment could not be processed.',
            self::ISSUED => 'Your payment has been issued successfully.',
            self::CANCELLED => 'Your payment was cancelled.',
        };
    }
}
