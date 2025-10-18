<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserReservationsTable extends Component
{
    public $reservations = [];

    public function mount()
    {
        $user = Auth::user();

        if ($user) {
            $this->reservations = $user->reservations()->with('car')->latest()->get();
        }
    }

    public function render()
    {
        return view('livewire.user-reservations-table');
    }
}
