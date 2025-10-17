<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
