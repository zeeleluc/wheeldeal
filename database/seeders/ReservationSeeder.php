<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Car;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $cars = Car::all();

        foreach ($cars as $car) {
            $startDate = Carbon::now()->subMonth();

            for ($i = 0; $i < 2; $i++) {
                $duration = rand(2, 14);

                Reservation::factory()
                    ->forCar($car)
                    ->forUser($this->getRandomUser())
                    ->startingAt($startDate)
                    ->duration($duration)
                    ->create();

                $startDate = $startDate->copy()->addDays($duration + rand(0, 3));
            }
        }
    }

    private function getRandomUser(): User
    {
        if (rand(1, 5) === 1) {
            return User::inRandomOrder()->first() ?? User::factory()->create();
        }

        return User::factory()->create();
    }
}
