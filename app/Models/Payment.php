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
}
