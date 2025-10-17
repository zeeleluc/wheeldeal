<?php

namespace App\Models;

use App\Services\EndDateService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'passengers',
        'total_price_cents',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function getDaysAttribute(): int
    {
        return EndDateService::calculateDuration($this->start_date, $this->end_date);
    }
}
