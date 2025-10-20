<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Services\Sentoo;

class SyncPendingPayments extends Command
{
    protected $signature = 'payments:sync-pending';
    protected $description = 'Check pending payments and update reservation/payment status via Sentoo API';

    protected Sentoo $sentoo;

    public function __construct(Sentoo $sentoo)
    {
        parent::__construct();
        $this->sentoo = $sentoo;
    }

    public function handle(): void
    {
        $this->info('Checking pending payments...');

        $reservations = Reservation::with('payments')
            ->where('status', 'pending_payment')
            ->get();

        foreach ($reservations as $reservation) {
            $pendingPayment = $reservation->payments()
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$pendingPayment) {
                continue;
            }

            $this->info("Checking reservation #{$reservation->id}, payment #{$pendingPayment->id}");

            $status = $this->sentoo->fetchStatus($pendingPayment->identification);

            if ($status && $status !== $pendingPayment->status) {
                $pendingPayment->markAs($status);

                if (PaymentStatus::SUCCESS->value === $status->value) {
                    $reservation->pay();
                } elseif (in_array($status->value, ['issued', 'cancelled', 'failed', 'rejected'])) {
                    $reservation->setStatus('cancelled');
                }

                $this->info("Updated reservation #{$reservation->id} and payment #{$pendingPayment->id} to status {$status->value}");
            }
        }

        $this->info('Pending payments sync completed.');
    }
}
