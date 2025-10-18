<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class CleanDraftReservations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reservations:clean-drafts';

    /**
     * The console command description.
     */
    protected $description = 'Delete draft reservations (user_id is null) older than 10 minutes';

    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        $threshold = Carbon::now()->subMinutes(10);

        $deleted = Reservation::whereNull('user_id')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Deleted {$deleted} draft reservation(s).");

        return true;
    }
}
