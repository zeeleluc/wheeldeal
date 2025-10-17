<?php

namespace App\Livewire;

use Livewire\Component;

abstract class Modal extends Component
{
    public bool $isOpen = false;

    abstract protected function resetInputFields(): void;

    public function openModal(): void
    {
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetValidation();
        $this->resetInputFields();
        $this->isOpen = false;
    }
}
