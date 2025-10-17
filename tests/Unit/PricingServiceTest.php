<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PricingService;
use Carbon\Carbon;
use Tests\TestCaseHelpers\BaseTestHelpers;

class PricingServiceTest extends TestCase
{
    use BaseTestHelpers;
    use RefreshDatabase;

    public function testBasePriceWithoutMultipliers()
    {
        $car = $this->createCar(['base_price_cents' => 10000]);
        $start = Carbon::parse('2025-05-01');
        $end = Carbon::parse('2025-05-03');

        $price = PricingService::calculatePrice($car, $start, $end);

        $this->assertEquals(10000 * 3, $price['total']);
        $this->assertEquals(3, $price['days']);
        $this->assertEquals(10000, $price['daily']);
    }

    public function testSeasonalMultiplierIsApplied()
    {
        $car = $this->createCar(['base_price_cents' => 10000]);
        $start = Carbon::parse('2025-07-10');
        $end = Carbon::parse('2025-07-12');

        $price = PricingService::calculatePrice($car, $start, $end);
        $expectedTotal = (int) (10000 * 3 * 1.25);

        $this->assertEquals($expectedTotal, $price['total']);
    }

    public function testDemandMultiplierIsApplied()
    {
        $cars = [];
        for ($i = 0; $i < 5; ++$i) {
            $cars[] = $this->createCar(['type' => 'sedan']);
        }

        foreach (array_slice($cars, 0, 4) as $car) {
            $this->createReservation([
                'car' => $car,
                'start_date' => Carbon::today(),
                'duration' => 2,
            ]);
        }

        $price = PricingService::calculatePrice(
            $cars[4],
            Carbon::today(),
            Carbon::today()->addDay()
        );

        $this->assertGreaterThan(10000 * 2, $price['total']);
    }

    public function testAvailabilityMultiplierIsApplied()
    {
        $cars = [];
        for ($i = 0; $i < 5; ++$i) {
            $cars[] = $this->createCar(['type' => 'sedan']);
        }

        foreach (array_slice($cars, 0, 4) as $car) {
            $this->createReservation([
                'car' => $car,
                'start_date' => Carbon::today(),
                'duration' => 2,
            ]);
        }

        $price = PricingService::calculatePrice(
            $cars[4],
            Carbon::today(),
            Carbon::today()->addDay()
        );

        $this->assertGreaterThan(10000 * 2, $price['total']);
    }

    public function testCombinedMultipliers()
    {
        $cars = [];
        for ($i = 0; $i < 5; ++$i) {
            $cars[] = $this->createCar(['type' => 'sedan']);
        }

        foreach (array_slice($cars, 0, 4) as $car) {
            $this->createReservation([
                'car' => $car,
                'start_date' => Carbon::parse('2025-07-01'),
                'duration' => 3,
            ]);
        }

        $price = PricingService::calculatePrice(
            $cars[4],
            Carbon::parse('2025-07-01'),
            Carbon::parse('2025-07-03')
        );

        $this->assertGreaterThan(10000 * 3, $price['total']);
    }
}
