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

        return [
            'name' => ucfirst($type) . ' ' . $this->faker->unique()->numerify('###'),
            'license_plate' => strtoupper($this->faker->unique()->bothify('??-###-??')),
            'type' => $carTypeEnum->value,
            'capacity' => $carTypeEnum->capacity(),
            'base_price_cents' => $this->faker->numberBetween(2000, 8000),
            'apk_expiry' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
        ];
    }
}
