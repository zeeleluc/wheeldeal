<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\CarType;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(CarType::values());
        $carTypeEnum = CarType::from($type);

        $basePriceCents = match($carTypeEnum) {
            CarType::CONVERTIBLE => $this->faker->numberBetween(3000, 4000),
            CarType::SEDAN => $this->faker->numberBetween(5000, 6000),
            CarType::MINIVAN => $this->faker->numberBetween(7000, 8000),
        };

        return [
            'name' => ucfirst($type) . ' ' . $this->faker->unique()->numerify('###'),
            'type' => $carTypeEnum->value,
            'license_plate' => strtoupper($this->faker->unique()->bothify('??###??')),
            'capacity' => $carTypeEnum->capacity(),
            'base_price_cents' => $basePriceCents,
            'apk_expiry' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
        ];
    }
}
