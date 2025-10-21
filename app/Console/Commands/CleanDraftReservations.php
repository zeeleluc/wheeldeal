<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Enums\ReservationType;
use Carbon\Carbon;

class CleanDraftReservations extends Command
{
    protected $signature = 'reservations:clean-drafts';
    protected $description = 'Mark old draft or pending payment reservations as aborted';

    public function handle(): bool
    {
        $draftThreshold = Carbon::now()->subMinutes(config('car.expiration.draft_minutes'));
        $pendingPaymentThreshold = Carbon::now()->subMinutes(config('car.expiration.pending_payment_minutes'));

        $abortedDrafts = Reservation::whereNull('user_id')
            ->where('created_at', '<', $draftThreshold)
            ->where('status', ReservationType::DRAFT)
            ->update(['status' => ReservationType::ABORTED]);

        $abortedPending = Reservation::whereIn('status', [
            ReservationType::PENDING_PAYMENT,
            ReservationType::CANCELLED,
        ])
            ->where('created_at', '<', $pendingPaymentThreshold)
            ->update(['status' => ReservationType::ABORTED]);

        $this->info("Marked {$abortedDrafts} draft reservation(s) as aborted.");
        $this->info("Marked {$abortedPending} pending/cancelled reservation(s) as aborted.");

        return true;
    }
}
