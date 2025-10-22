<?php

namespace App\Models;

use App\Enums\ReservationType;
use App\Helpers\ReservationStatusHelper;
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

    public function resolveStatus(): void
    {
        ReservationStatusHelper::resolve($this);
    }

    public function paid(?Carbon $date = null): void
    {
        $this->paid_at = $date ?? Carbon::now();
        $this->setStatus(ReservationType::PAID);
    }
}
