<?php

namespace Tests\TestCaseHelpers;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        $status = $overrides['status'] ?? null;
        $start = $overrides['start_date'] ?? Carbon::today();
        $duration = $overrides['duration'] ?? 3;

        $factory = Reservation::factory()
            ->forCar($car)
            ->forUser($user)
            ->startingAt($start)
            ->duration($duration)
            ->status($status);

        return $factory->create();
    }

    protected function createPayment(Reservation $reservation, ?PaymentStatus $status = null): Payment
    {
        if (!$status) {
            $status = PaymentStatus::SUCCESS;
        }

        return Payment::create([
            'reservation_id' => $reservation->id,
            'identification' => 'seeded-'.Str::uuid(),
            'status' => $status,
        ]);
    }
}
