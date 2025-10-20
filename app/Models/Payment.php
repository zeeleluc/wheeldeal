<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'identification',
        'status',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function markAs(PaymentStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function markAsSuccess(): void
    {
        $this->markAs(PaymentStatus::SUCCESS);
    }

    public function markAsPending(): void
    {
        $this->markAs(PaymentStatus::PENDING);
    }

    public function markAsRejected(): void
    {
        $this->markAs(PaymentStatus::REJECTED);
    }

    public function markAsFailed(): void
    {
        $this->markAs(PaymentStatus::FAILED);
    }

    public function markAsIssued(): void
    {
        $this->markAs(PaymentStatus::ISSUED);
    }

    public function markAsCancelled(): void
    {
        $this->markAs(PaymentStatus::CANCELLED);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::SUCCESS;
    }
}
