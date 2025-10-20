<?php

namespace App\Enums;

enum CarType: string
{
    case MINIVAN = 'minivan';
    case SEDAN = 'sedan';
    case CONVERTIBLE = 'convertible';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function capacity(): int
    {
        return match ($this) {
            self::MINIVAN => 7,
            self::SEDAN => 5,
            self::CONVERTIBLE => 2,
        };
    }

    public function title(): string
    {
        return match ($this) {
            self::MINIVAN => 'Minivan',
            self::SEDAN => 'Sedan',
            self::CONVERTIBLE => 'Convertible',
        };
    }
}
