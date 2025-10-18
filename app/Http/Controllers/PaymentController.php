<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function show(Reservation $reservation)
    {
        if (!Gate::check('pay', $reservation)) {
            return redirect()->route('user.show', $reservation->user_id);
        }

        return view('payment.show', compact('reservation'));
    }

    public function pay(Request $request, Reservation $reservation)
    {
        Gate::authorize('pay', $reservation);

        $reservation->pay();

        return redirect()->route('reservations.show', $reservation);
    }

    public function status(string $status)
    {
        try {
            $statusEnum = PaymentStatus::from($status);
        } catch (\ValueError $e) {
            abort(404);
        }

        return view('payment.status', compact('statusEnum'));
    }
}
