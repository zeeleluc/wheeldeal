<?php

namespace App\Services;

use Carbon\Carbon;

class EndDateService
{
    public static function calculateEndDate(Carbon $startDate, int $duration): Carbon
    {
        return $startDate->copy()->addDays(max($duration, 1) - 1);
    }

    public static function calculateDuration(Carbon $startDate, Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate) + 1;
    }
}
