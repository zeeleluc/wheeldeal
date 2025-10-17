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
            'license_plate' => $this->generateDutchLicensePlate(),
            'type' => $carTypeEnum->value,
            'capacity' => $carTypeEnum->capacity(),
            'base_price_cents' => $this->faker->numberBetween(2000, 8000),
            'apk_expiry' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
        ];
    }

    private function generateDutchLicensePlate(): string
    {
        $faker = $this->faker;
        $patterns = [
            'A###AA',   // A-001-AA
            'AA###A',   // AA-001-A
            '##AAA#',   // 00-AAA-1
            '#AAA##',   // 0-AAA-01
            'AAA##A',   // AAA-01-A
            'A##AAA',   // A-01-AAA
            '#AA###',   // 0-AA-001
        ];

        $pattern = $faker->randomElement($patterns);

        return strtoupper($faker->bothify($pattern));
    }
}
