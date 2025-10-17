<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function createReservation(array $overrides = []): Reservation
    {
        $car = $overrides['car'] ?? Car::factory()->create([
            'base_price_cents' => $overrides['base_price_cents'] ?? 10000,
            'capacity' => $overrides['capacity'] ?? 5,
        ]);

        $user = $overrides['user'] ?? User::factory()->create();

        $startDate = $overrides['start_date'] ?? Carbon::now();
        $duration = $overrides['duration'] ?? 3;

        return Reservation::factory()
            ->forCar($car)
            ->forUser($user)
            ->startingAt($startDate)
            ->duration($duration)
            ->create();
    }

    public function testTotalPriceIsCorrect()
    {
        $duration = 4;
        $basePrice = 12000;

        $reservation = $this->createReservation([
            'duration' => $duration,
            'base_price_cents' => $basePrice,
        ]);

        $expectedPrice = $basePrice * $duration;

        $this->assertEquals($expectedPrice, $reservation->total_price_cents);
    }

    public function testDurationIsCalculatedCorrectly()
    {
        $duration = 5;
        $reservation = $this->createReservation(['duration' => $duration]);

        $this->assertEquals($duration, $reservation->days);
    }

    public function testEndDateIsCalculatedCorrectly()
    {
        $startDate = Carbon::parse('2025-08-20');
        $duration = 3;

        $reservation = $this->createReservation([
            'start_date' => $startDate,
            'duration' => $duration,
        ]);

        $expectedEndDate = Carbon::parse('2025-08-22');

        $this->assertTrue($reservation->end_date->eq($expectedEndDate));
    }
}
