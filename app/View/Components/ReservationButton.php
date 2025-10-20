<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Reservation;

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

        $this->disabled = $user && !Gate::allows('create', [Reservation::class, $user]);
    }

    public function render()
    {
        return view('components.reservation-button');
    }
}
