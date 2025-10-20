<?php

namespace Database\Factories;

use App\Enums\ReservationType;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use App\Services\EndDateService;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\PricingService;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    protected ?Car $car = null;

    protected ?User $user = null;

    protected ?Carbon $startingAt = null;

    protected ?int $durationDays = null;

    public ?ReservationType $status = null;

    public function definition(): array
    {
        $endDate = EndDateService::calculateEndDate($this->startingAt, $this->durationDays);
        $totalPriceCents = PricingService::calculatePrice($this->car, $this->startingAt, $endDate);

        return [
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'status' => $this->status ?? ReservationType::PAID,
            'start_date' => $this->startingAt,
            'end_date' => $endDate,
            'passengers' => $this->faker->numberBetween(1, $this->car->capacity),
            'total_price_cents' => $totalPriceCents['total'],
            'paid_at' => $this->startingAt
                ->clone()
                ->subDays(rand(1, 7))
                ->subHours(rand(1, 20))
                ->subMinutes(rand(1, 50))
                ->subSeconds(rand(1, 50)),
        ];
    }

    public function forCar(Car $car): self
    {
        $factory = clone $this;
        $factory->car = $car;

        return $factory;
    }

    public function forUser(User $user): self
    {
        $factory = clone $this;
        $factory->user = $user;

        return $factory;
    }

    public function startingAt(Carbon $date): self
    {
        $factory = clone $this;
        $factory->startingAt = $date;

        return $factory;
    }

    public function duration(int $days): self
    {
        $factory = clone $this;
        $factory->durationDays = $days;

        return $factory;
    }

    public function status(?ReservationType $status = null): self
    {
        $factory = clone $this;
        if ($status) {
            $factory->status = $status;
        }
        return $factory;
    }
}
