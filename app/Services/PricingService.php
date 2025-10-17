<?php

namespace App\Services;

use App\Models\Car;
use Carbon\Carbon;

class PricingService
{
    public static function calculatePrice(Car $car, Carbon $startDate, Carbon $endDate): array
    {
        $start = $startDate->copy();
        $end = $endDate->copy();

        $days = $start->diffInDays($end) + 1;

        $totalPriceCents = $days * $car->base_price_cents;

        $totalPriceCents = self::applyDynamicPricing($car, $start, $end, $totalPriceCents);

        $dailyPriceCents = intdiv($totalPriceCents, $days);

        return [
            'total' => $totalPriceCents,
            'daily' => $dailyPriceCents,
            'days' => $days,
        ];
    }

    protected static function applyDynamicPricing(Car $car, Carbon $start, Carbon $end, int $currentPriceCents): int
    {
        // @TODO
        // 1. Factor in demand: increase price if car is highly booked
        // 2. Factor in season: higher price during holidays / peak months
        // 3. Factor in availability: few cars left = increase price

        return $currentPriceCents;
    }
}
