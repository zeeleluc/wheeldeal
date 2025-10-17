<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Reservation::truncate();
        Car::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            AdminUserSeeder::class,
            CarSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
