<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
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
    // ----- State -----
    public int $step = 1;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $end_date_max = null;
    public int $passengers = 2;

    public ?Collection $availableCars = null;
    public Collection $alternativeDates;
    public ?int $selectedCarId = null;
    public ?int $quoteCents = null;
    public ?int $dailyPriceCents = null;
    public ?int $durationDays = null;

    protected $rules = [];

    // ----- Lifecycle -----
    public function mount(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->step = 1;
        $this->start_date = Carbon::tomorrow()->toDateString();
        $this->end_date = Carbon::tomorrow()->addWeek()->toDateString();
        $this->passengers = 2;

        $this->availableCars = null;
        $this->alternativeDates = collect();
        $this->selectedCarId = null;
        $this->quoteCents = null;
        $this->dailyPriceCents = null;
        $this->durationDays = null;

        $this->end_date_max = Carbon::tomorrow()
            ->addDays(config('car.rental.max_days') - 1)
            ->toDateString();
    }

    // ----- Step Navigation -----
    public function nextStep(): void
    {
        $rentalConfig = config('car.rental');

        if (1 === $this->step) {
            $this->validateStep1($rentalConfig);
            $this->fetchAvailableCars();

            if ($this->availableCars->isNotEmpty()) {
                $this->alternativeDates = collect();
                $this->step = 2;
            } else {
                $this->suggestAlternativeDates(
                    Carbon::parse($this->start_date),
                    Carbon::parse($this->end_date)
                );
            }
        } elseif (2 === $this->step) {
            $this->validate(['selectedCarId' => ['required', 'exists:cars,id']]);
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

    protected function validateStep1($rentalConfig): void
    {
        $this->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => [
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
            'passengers' => ['required', 'integer', 'min:1', 'max:7'],
        ]);
    }

    // ----- Livewire Hooks / User Updates -----
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

    public function updatedPassengers($value)
    {
        if ($this->alternativeDates->isNotEmpty()) {
            $this->clearAlternativeDates();
        }
    }

    // ----- Car Availability & Pricing -----
    protected function fetchAvailableCars(): void
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $cars = Car::where('capacity', '>=', $this->passengers)
            ->get()
            ->filter(fn ($car) => $car->isAvailableForPeriod($start, $end))
            ->sortBy('base_price_cents')
            ->take(5)
            ->values();

        $this->availableCars = $cars->map(fn ($car) => [
            'id' => $car->id,
            'name' => $car->name,
            'license_plate' => $car->formatted_license_plate,
            'capacity' => $car->capacity,
            'dailyPriceCents' => PricingService::calculatePrice($car, $start, $end)['daily'],
            'totalPriceCents' => PricingService::calculatePrice($car, $start, $end)['total'],
        ]);
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

    // ----- Alternative Dates -----
    public function selectAlternativeDates(string $start, string $end): void
    {
        $this->start_date = $start;
        $this->end_date = $end;
        $this->fetchAvailableCars();

        if ($this->availableCars->isNotEmpty()) {
            $this->alternativeDates = collect();
            $this->step = 2;
        }
    }

    protected function suggestAlternativeDates(Carbon $start, Carbon $end): void
    {
        $this->alternativeDates = collect();
        $maxDaysToShift = config('car.rental.max_days_shift');

        for ($i = 1; $i <= $maxDaysToShift; ++$i) {
            $newStart = $start->copy()->addDays($i);
            $newEnd = $end->copy()->addDays($i);

            $availableCars = Car::where('capacity', '>=', $this->passengers)
                ->get()
                ->filter(fn ($car) => $car->isAvailableForPeriod($newStart, $newEnd))
                ->values();

            if ($availableCars->isNotEmpty()) {
                $this->alternativeDates->push([
                    'start_date' => $newStart->toDateString(),
                    'end_date' => $newEnd->toDateString(),
                    'cars' => $availableCars,
                ]);
            }
        }
    }

    public function clearAlternativeDates(): void
    {
        $this->alternativeDates = collect();
    }

    // ----- Booking -----
    public function book(): Redirector
    {
        $this->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'selectedCarId' => ['required', 'exists:cars,id'],
        ]);

        $userId = Auth::id();

        $reservation = Reservation::create([
            'user_id' => $userId,
            'car_id' => $this->selectedCarId,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'passengers' => $this->passengers,
            'total_price_cents' => $this->quoteCents,
        ]);

        if (!$userId) {
            Session::put('draft_reservation_id', $reservation->id);

            return redirect()->route('login');
        }

        return redirect()->route('payment.show', $reservation);
    }

    // ----- Render -----
    public function render(): View
    {
        return view('livewire.reservation-form');
    }
}
