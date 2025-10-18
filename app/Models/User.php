<?php

namespace App\Models;

use App\Enums\ReservationType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isAdmin(): bool
    {
        return 'admin' === $this->role;
    }

    public function hasRecentReservation(): bool
    {
        $frequencyDays = config('car.rental.frequency_days');

        return $this->reservations()
            ->where(function ($query) {
                $query->where('status', ReservationType::PENDING_PAYMENT)
                    ->orWhere('status', ReservationType::PAID);
            })
            ->where('created_at', '>=', Carbon::now()->subDays($frequencyDays))
            ->exists();
    }
}
