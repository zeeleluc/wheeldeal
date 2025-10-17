<?php

namespace Tests\TestCaseHelpers;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

trait BaseTestHelpers
{
    protected function createCar(array $overrides = []): Car
    {
        return Car::factory()->create(array_merge([
            'base_price_cents' => 10000,
            'type' => 'sedan',
        ], $overrides));
    }

    protected function createReservation(array $overrides = []): Reservation
    {
        $car = $overrides['car'] ?? $this->createCar();
        $user = $overrides['user'] ?? User::factory()->create();
        $start = $overrides['start_date'] ?? Carbon::today();
        $duration = $overrides['duration'] ?? 3;

        return Reservation::factory()
            ->forCar($car)
            ->forUser($user)
            ->startingAt($start)
            ->duration($duration)
            ->create();
    }
}
