<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Reservation;
use App\Services\Sentoo;

class PayButton extends Component
{
    public Reservation $reservation;
    public string $currency = 'XCG';
    public string $description = '';
    public bool $loading = false;
    public ?string $error = null;

    public function pay()
    {
        $this->reset(['error']);

        if (!Gate::allows('pay', $this->reservation)) {
            $this->error = __('You are not authorized to pay for this reservation.');

            return;
        }

        try {
            $sentoo = app(Sentoo::class);

            $result = $sentoo->createPayment(
                $this->reservation,
                $this->currency,
                $this->description ?: "Reservation #{$this->reservation->id}"
            );

            if ($result && isset($result['url'])) {
                $this->js('window.location.href = "'.addslashes($result['url']).'"');

                return;
            }

            $this->error = __('Unable to create payment.');
        } catch (\Throwable $e) {
            $this->error = __('Something went wrong while creating payment.');
        }
    }

    public function render()
    {
        return view('livewire.pay-button');
    }
}
