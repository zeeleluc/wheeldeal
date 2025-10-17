<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Car;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $uniquePlates = $this->generateUniqueLicensePlates(20);

        foreach ($uniquePlates as $plate) {
            Car::factory()->create([
                'license_plate' => $plate,
            ]);
        }
    }

    private function generateUniqueLicensePlates(int $count): array
    {
        $faker = Factory::create();
        $patterns = [
            '?###??',   // A001AA
            '??###?',   // AA001A
            '##???#',   // 00AAA1
            '#???##',   // 0AAA01
            '???##?',   // AAA01A
            '?##???',   // A01AAA
            '#??###',   // 0AA001
        ];

        $plates = [];

        while (count($plates) < $count) {
            $pattern = $faker->randomElement($patterns);
            $plate = strtoupper($faker->bothify($pattern));

            if (!in_array($plate, $plates)) {
                $plates[] = $plate;
            }
        }

        return $plates;
    }
}
