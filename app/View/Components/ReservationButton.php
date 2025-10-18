<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class ReservationButton extends Component
{
    public string $class;
    public bool $disabled;
    public string $title;

    public function __construct(string $title = 'New Reservation', string $class = '')
    {
        $this->title = $title;
        $this->class = $class;

        $user = Auth::user();

        $this->disabled = $user ? $user->hasRecentReservation() : false;
    }

    public function render()
    {
        return view('components.reservation-button');
    }
}
