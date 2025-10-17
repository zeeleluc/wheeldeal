<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Car;
use App\Models\Reservation;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class ReservationForm extends Component
{
    public int $step = 1;

    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $end_date_max = null;
    public int $passengers = 2;

    public ?Collection $availableCars = null;
    public ?int $selectedCarId = null;
    public ?int $quoteCents = null;
    public ?int $dailyPriceCents = null;
    public ?int $durationDays = null;

    protected $rules = [];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatedStartDate($value)
    {
        $start = Carbon::parse($value);
        $rentalConfig = config('car.rental');

        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

        if (!$end || $end->lt($start)) {
            $daysToAdd = max(7, $rentalConfig['min_days']) - 1;
            $this->end_date = $start->copy()->addDays($daysToAdd)->toDateString();
        }

        $this->end_date_max = $start->copy()->addDays($rentalConfig['max_days'] - 1)->toDateString();
    }

    public function nextStep(): void
    {
        $rentalConfig = config('car.rental');

        if (1 === $this->step) {
            $this->validate([
                'start_date' => ['required', 'date', 'after_or_equal:today'],
                'end_date'   => [
                    'required', 'date', 'after_or_equal:start_date',
                    function ($attribute, $value, $fail) use ($rentalConfig) {
                        $start = Carbon::parse($this->start_date);
                        $end = Carbon::parse($value);
                        $days = $start->diffInDays($end) + 1;

                        if ($days < $rentalConfig['min_days']) {
                            $fail("The rental must be at least {$rentalConfig['min_days']} day(s).");
                        }

                        if ($days > $rentalConfig['max_days']) {
                            $fail("The rental may not exceed {$rentalConfig['max_days']} day(s).");
                        }
                    },
                ],
                'passengers' => ['required', 'integer', 'min:1'],
            ]);

            $this->fetchAvailableCars();
            $this->step = 2;
        } elseif (2 === $this->step) {
            $this->validate([
                'selectedCarId' => ['required', 'exists:cars,id'],
            ]);

            $this->calculateQuote();
            $this->step = 3;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            --$this->step;
        }
    }

    protected function fetchAvailableCars(): void
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $this->availableCars = Car::where('capacity', '>=', $this->passengers)
            ->get()
            ->filter(fn ($car) => $car->isAvailableForPeriod($start, $end))
            ->values();
    }

    protected function calculateQuote(): void
    {
        $car = Car::findOrFail($this->selectedCarId);
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $pricing = PricingService::calculatePrice($car, $start, $end);

        $this->quoteCents = $pricing['total'];
        $this->dailyPriceCents = $pricing['daily'];
        $this->durationDays = $pricing['days'];
    }

    public function book(): Redirector
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'selectedCarId' => ['required', 'exists:cars,id'],
        ]);

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'car_id' => $this->selectedCarId,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'passengers' => $this->passengers,
            'total_price_cents' => $this->quoteCents,
        ]);

        session()->flash('success', 'Reservation completed!');

        $this->resetForm();

        return redirect()->route('reservations.show', $reservation);
    }

    protected function resetForm(): void
    {
        $this->step = 1;
        $this->start_date = Carbon::tomorrow()->toDateString();
        $this->end_date = Carbon::tomorrow()->addWeek()->toDateString();
        $this->passengers = 2;

        $this->availableCars = null;
        $this->selectedCarId = null;
        $this->quoteCents = null;
        $this->dailyPriceCents = null;
        $this->durationDays = null;

        $this->end_date_max = Carbon::tomorrow()->addDays(config('car.rental.max_days') - 1)->toDateString();
    }

    public function render(): View
    {
        return view('livewire.reservation-form');
    }
}
