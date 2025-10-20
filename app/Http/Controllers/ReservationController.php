<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if (!Gate::check('create', new Reservation())) {
            if ($user) {
                return redirect()->route('user.show', $user);
            }

            return redirect()->route('welcome');
        }

        return view('reservations.create');
    }

    public function show(Reservation $reservation)
    {
        Gate::authorize('view', $reservation);

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }
}
