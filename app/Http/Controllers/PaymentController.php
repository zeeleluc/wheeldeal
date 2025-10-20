<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Services\Sentoo;

class PaymentController extends Controller
{
    public function __construct(protected Sentoo $sentoo)
    {

    }

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

        $redirectUrl = route('user.show', auth()->id());

        return view('payment.status', compact('statusEnum', 'redirectUrl'));
    }

    public function webhook(Request $request)
    {
        Log::info('Sentoo webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        $this->sentoo->handleWebhook($request->all());

        return response('success', 200);
    }
}
