<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Enums\ReservationType;
use Carbon\Carbon;

class CleanDraftReservations extends Command
{
    protected $signature = 'reservations:clean-drafts';
    protected $description = 'Delete old draft reservations or pending payments';

    public function handle(): bool
    {
        $draftThreshold = Carbon::now()->subMinutes(config('car.expiration.draft_minutes'));
        $pendingPaymentThreshold = Carbon::now()->subMinutes(config('car.expiration.pending_payment_minutes'));

        $deletedDrafts = Reservation::whereNull('user_id')
            ->where('created_at', '<', $draftThreshold)
            ->delete();

        $deletedPending = Reservation::where('status', ReservationType::PENDING_PAYMENT)
            ->where('created_at', '<', $pendingPaymentThreshold)
            ->delete();

        $this->info("Deleted {$deletedDrafts} draft reservation(s).");
        $this->info("Deleted {$deletedPending} pending payment reservation(s).");

        return true;
    }
}
