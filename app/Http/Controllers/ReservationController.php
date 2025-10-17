<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function show(Reservation $reservation)
    {
        Gate::authorize('view', $reservation);

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }
}
