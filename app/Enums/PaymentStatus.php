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

    public function label(): string
    {
        return match($this) {
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
        return match($this) {
            self::SUCCESS => 'green',
            self::PENDING => 'orange',
            self::REJECTED, self::FAILED => 'red',
            self::ISSUED => 'blue',
            self::CANCELLED => 'gray',
        };
    }

    public function circleClass(): string
    {
        return match($this) {
            self::SUCCESS => 'bg-green-500',
            self::PENDING => 'border-4 border-orange-400 border-t-orange-600 animate-spin rounded-full',
            self::REJECTED, self::FAILED => 'bg-red-500',
            self::ISSUED => 'bg-blue-500',
            self::CANCELLED => 'bg-gray-400',
        };
    }

    public function message(): string
    {
        return match($this) {
            self::SUCCESS => 'Your payment was successful. Thank you!',
            self::PENDING => 'Your payment is pending. Please wait for confirmation.',
            self::REJECTED, self::FAILED => 'Your payment could not be processed. Please try again.',
            self::ISSUED => 'Your payment has been issued successfully.',
            self::CANCELLED => 'Your payment was cancelled.',
        };
    }
}
