<?php

namespace App\Livewire;

use Livewire\Component;

class DraftReservationBar extends Component
{
    public function render()
    {
        $hasDraft = session()->has('draft_reservation_id');

        return view('livewire.draft-reservation-bar', [
            'hasDraft' => $hasDraft,
        ]);
    }
}
