<?php

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RentalFrequencyAlert extends Component
{
    public bool $showAlert = false;

    public function mount(): void
    {
        $user = Auth::user();
        if (!Gate::allows('create', [Reservation::class, $user])) {
            $this->showAlert = true;
        }
    }

    public function render(): View
    {
        return view('livewire.rental-frequency-alert');
    }
}
