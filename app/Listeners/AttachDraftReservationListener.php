<?php

namespace App\Listeners;

use App\Events\DraftReservationAssigned;
use App\Models\Reservation;

class AttachDraftReservationListener
{
    public function handle(DraftReservationAssigned $event): void
    {
        if ($draft = Reservation::find($event->draftId)) {
            $draft->update(['user_id' => $event->user->id]);
        }
    }
}
