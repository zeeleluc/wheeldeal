<?php

namespace App\Models;

use App\Enums\CarType;
use App\Enums\ReservationType;
use App\Utils\DutchLicensePlatePatterns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'license_plate',
        'type',
        'capacity',
        'base_price_cents',
        'apk_expiry',
    ];

    protected $casts = [
        'apk_expiry' => 'date',
        'type' => CarType::class,
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    protected function getFormattedLicensePlateAttribute(): string
    {
        $value = strtoupper(str_replace(['-', ' '], '', $this->license_plate));

        foreach (DutchLicensePlatePatterns::formattingPatterns() as $pattern => $replacement) {
            if (preg_match($pattern, $value)) {
                return preg_replace($pattern, $replacement, $value);
            }
        }

        return $value;
    }

    public function isAvailableForPeriod(Carbon $start, Carbon $end): bool
    {
        return !$this->reservations()
            ->where('status', '!=', ReservationType::CANCELLED->value)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }
}
