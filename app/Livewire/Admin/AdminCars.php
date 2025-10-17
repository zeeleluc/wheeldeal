<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Car;

class AdminCars extends Component
{
    public Collection $cars;

    protected $listeners = [
        'carAdded' => 'refreshCars',
        'carUpdated' => 'refreshCars',
    ];

    public function mount()
    {
        $this->cars = $this->getCars();
    }

    public function openModal($carId = null): void
    {
        $this->dispatch('admin.car-modal', 'openModal', id: $carId);
    }

    public function refreshCars(): void
    {
        $this->cars = $this->getCars();
    }

    public function render(): View
    {
        return view('livewire.admin.admin-cars');
    }

    private function getCars(): Collection
    {
        return Car::orderBy('created_at', 'desc')->get();
    }
}
