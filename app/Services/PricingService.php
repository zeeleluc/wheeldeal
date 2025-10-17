<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;

class PricingService
{
    public static function calculatePrice(Car $car, Carbon $startDate, Carbon $endDate): array
    {
        $days = $startDate->diffInDays($endDate) + 1;
        $basePrice = $days * $car->base_price_cents;

        $totalPriceCents = self::applyDynamicPricing($car, $startDate, $endDate, $basePrice);

        return [
            'total' => $totalPriceCents,
            'daily' => intdiv($totalPriceCents, $days),
            'days' => $days,
        ];
    }

    protected static function applyDynamicPricing(Car $car, Carbon $start, Carbon $end, int $price): int
    {
        $price = self::applySeasonalMultiplier($price, $start, $end);
        $price = self::applyDemandMultiplier($price, $car, $start, $end);
        $price = self::applyAvailabilityMultiplier($price, $car);

        return $price;
    }

    private static function applySeasonalMultiplier(int $price, Carbon $start, Carbon $end): int
    {
        $config = config('pricing.seasonal', []);

        foreach ($config as $season) {
            foreach ($season['months'] as $month) {
                if ($start->month === $month || $end->month === $month) {
                    return (int) ($price * $season['multiplier']);
                }
            }
        }

        return $price;
    }

    private static function applyDemandMultiplier(int $price, Car $car, Carbon $start, Carbon $end): int
    {
        $config = config('pricing.demand', []);
        $threshold = $config['threshold'];
        $multiplier = $config['multiplier'];

        $totalCarsOfType = Car::where('type', $car->type->value)->count();

        $bookedCarsOfType = Reservation::whereHas('car', function ($q) use ($car) {
            $q->where('type', $car->type->value);
        })->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                ->orWhereBetween('end_date', [$start, $end])
                ->orWhere(function ($q) use ($start, $end) {
                    $q->where('start_date', '<=', $start)
                        ->where('end_date', '>=', $end);
                });
        })->distinct('car_id')->count();

        if ($totalCarsOfType > 0 && ($bookedCarsOfType / $totalCarsOfType) >= $threshold) {
            return (int) ($price * $multiplier);
        }

        return $price;
    }

    private static function applyAvailabilityMultiplier(int $price, Car $car): int
    {
        $config = config('pricing.availability', []);
        $threshold = $config['threshold'];
        $multiplier = $config['multiplier'];

        $totalCarsOfType = Car::where('type', $car->type->value)->count();
        $availableCarsOfType = $totalCarsOfType - Reservation::where('car_id', $car->id)
                ->whereDate('end_date', '>=', now())
                ->count();

        if ($totalCarsOfType > 0 && ($availableCarsOfType / $totalCarsOfType) <= $threshold) {
            return (int) ($price * $multiplier);
        }

        return $price;
    }
}
