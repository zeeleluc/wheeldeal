<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ReservationType;
use App\Services\EndDateService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'status',
        'start_date',
        'end_date',
        'passengers',
        'total_price_cents',
        'paid_at',
    ];

    protected $casts = [
        'status' => ReservationType::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): ?Payment
    {
        return $this->payments()->latest()->first();
    }

    public function paymentByTransactionId(string $transactionId): ?Payment
    {
        return $this->payments()->where('transaction_id', $transactionId)->first();
    }

    public function hasSuccessfulPayment(): bool
    {
        return $this->payments()->where('status', PaymentStatus::SUCCESS)->exists();
    }

    public function hasPendingPayment(): bool
    {
        return $this->payments()->where('status', PaymentStatus::PENDING)->exists();
    }

    public function successfulPayments()
    {
        return $this->payments()->where('status', PaymentStatus::SUCCESS)->get();
    }

    public function getDaysAttribute(): int
    {
        return EndDateService::calculateDuration($this->start_date, $this->end_date);
    }

    public function setStatus(ReservationType|string $status): void
    {
        if (is_string($status)) {
            $status = ReservationType::from($status);
        }

        $this->status = $status;
        $this->save();
    }

    public function pay(?Carbon $date = null): void
    {
        if ($this->isPendingPayment()) {
            $this->paid_at = $date ?? Carbon::now();
            $this->setStatus(ReservationType::PAID);
        }
    }

    public function isPaid(): bool
    {
        return ! is_null($this->paid_at);
    }

    public function isActive(): bool
    {
        return ReservationType::PENDING_PAYMENT === $this->status
            || ReservationType::PAID === $this->status;
    }

    public function isPendingPayment(): bool
    {
        if (ReservationType::PENDING_PAYMENT !== $this->status) {
            return false;
        }

        if ($this->payments()->where('status', PaymentStatus::PENDING)->exists()) {
            return false;
        }

        return true;
    }

    public function isDraft(): bool
    {
        return ReservationType::DRAFT === $this->status;
    }

    public function isCancelled(): bool
    {
        return ReservationType::CANCELLED === $this->status;
    }
}
