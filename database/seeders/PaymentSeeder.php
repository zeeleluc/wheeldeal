<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Reservation;
use App\Models\Payment;
use App\Enums\PaymentStatus;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
            Payment::create([
                'reservation_id' => $reservation->id,
                'identification' => 'seeded-' . Str::uuid(),
                'status' => PaymentStatus::SUCCESS,
            ]);
        }
    }
}
