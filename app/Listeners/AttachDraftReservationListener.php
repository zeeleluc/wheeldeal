<?php

namespace App\Listeners;

use App\Enums\ReservationType;
use App\Events\DraftReservationAssigned;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class AttachDraftReservationListener
{
    public function handle(DraftReservationAssigned $event): void
    {
        $user = $event->user;

        if ($draft = Reservation::find($event->draftId)) {

            Log::info("Attaching draft reservation ID {$draft->id} to user ID {$user->id}.");

            $draft->update(['user_id' => $user->id]);

            if ($user->hasRecentReservation()) {
                $draft->setStatus(ReservationType::CANCELLED);
                Log::warning("Draft reservation ID {$draft->id} cancelled because user ID {$user->id} has a recent reservation.");
            } else {
                $draft->setStatus(ReservationType::PENDING_PAYMENT);
                Log::info("Draft reservation ID {$draft->id} set to pending payment for user ID {$user->id}.");
            }
        } else {
            Log::error("Draft reservation with ID {$event->draftId} not found.");
        }
    }
}
