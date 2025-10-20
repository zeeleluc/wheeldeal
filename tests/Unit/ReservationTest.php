<?php

namespace Tests\Unit;

use App\Enums\ReservationType;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCaseHelpers\BaseTestHelpers;

class ReservationTest extends TestCase
{
    use BaseTestHelpers;
    use RefreshDatabase;

    public function testTotalPriceIsCorrect()
    {
        $reservation = $this->createReservation([
            'duration' => 4,
            'car' => $this->createCar(['base_price_cents' => 12000]),
        ]);

        $this->assertEquals(12000 * 4, $reservation->total_price_cents);
    }

    public function testDurationIsCalculatedCorrectly()
    {
        $reservation = $this->createReservation(['duration' => 5]);
        $this->assertEquals(5, $reservation->days);
    }

    public function testEndDateIsCalculatedCorrectly()
    {
        $reservation = $this->createReservation([
            'start_date' => Carbon::parse('2025-08-20'),
            'duration' => 3,
        ]);

        $expectedEndDate = Carbon::parse('2025-08-22');
        $this->assertTrue($reservation->end_date->eq($expectedEndDate));
    }

    public function testCarCannotBeDoubleBooked()
    {
        $car = $this->createCar();

        $this->createReservation([
            'car' => $car,
            'start_date' => Carbon::parse('2025-09-01'),
            'duration' => 5,
        ]);

        $this->assertFalse($car->isAvailableForPeriod(
            Carbon::parse('2025-09-03'),
            Carbon::parse('2025-09-07')
        ));
    }

    public function testCarCanBeBookedOnDifferentDates()
    {
        $car = $this->createCar();
        $this->createReservation(['car' => $car, 'duration' => 5]);

        $this->assertTrue($car->isAvailableForPeriod(
            Carbon::parse('2025-09-10'),
            Carbon::parse('2025-09-12')
        ));
    }

    public function testUserCanBookIfNoRecentReservation()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasRecentReservation());
    }

    public function testUserCannotBookIfHasRecentReservation()
    {
        $user = User::factory()->create();
        $car = $this->createCar();

        $this->createReservation([
            'car' => $car,
            'user' => $user,
            'start_date' => Carbon::parse(now()->format('Y-m-d')),
            'duration' => 5,
        ]);

        $this->assertTrue($user->hasRecentReservation());
    }

    public function testUserCanBookIfHasOldReservation()
    {
        $user = User::factory()->create();
        $car = $this->createCar();

        $reservation = $this->createReservation([
            'car' => $car,
            'user' => $user,
            'start_date' => Carbon::parse(now()->format('Y-m-d')),
            'duration' => 5,
        ]);

        $reservation->created_at = Carbon::parse(now()->subMonth()->format('Y-m-d'));
        $reservation->save();

        $this->assertFalse($user->hasRecentReservation());
    }

    public function testCancelledReservationDoesNotBlockAvailability()
    {
        $car = $this->createCar();

        $this->createReservation([
            'car' => $car,
            'start_date' => Carbon::parse('2025-09-01'),
            'duration' => 5,
            'status' => ReservationType::CANCELLED,
        ]);

        $this->assertTrue($car->isAvailableForPeriod(
            Carbon::parse('2025-09-01'),
            Carbon::parse('2025-09-05')
        ));
    }
}
