<?php

namespace App\Livewire\Admin;

use App\Enums\CarType;
use App\Livewire\Modal;
use App\Models\Car;
use App\Rules\DutchLicensePlate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarModal extends Modal
{
    public ?int $carId = null;
    public string $name = '';
    public string $license_plate = '';
    public string $type = '';
    public int $capacity = 1;
    public int $amount;
    public int $cents;
    public ?string $apk_expiry = null;

    protected $listeners = ['openModal' => 'handleOpenModal'];

    #[\Livewire\Attributes\On('admin.car-modal')]
    public function handleOpenModal($id = null): void
    {
        if ($id) {
            $car = Car::findOrFail($id);
            $this->carId = $id;
            $this->name = $car->name;
            $this->license_plate = $car->license_plate;
            $this->type = $car->type->value;
            $this->capacity = $car->capacity;
            $this->amount = floor($car->base_price_cents / 100);
            $this->cents = str_pad($car->base_price_cents % 100, 2, '0', STR_PAD_LEFT);
            $this->apk_expiry = $car->apk_expiry->format('Y-m-d');
        } else {
            $this->resetInputFields();
        }

        $this->openModal();
    }

    protected function rules(): array
    {
        $minDate = now()->addDays(config('car.apk.min_days'))->toDateString();
        $maxDate = now()->addYears(config('car.apk.max_years'))->toDateString();

        return [
            'name' => ['required', 'string', 'max:255'],
            'license_plate' => [
                'required',
                new DutchLicensePlate(),
                Rule::unique('cars', 'license_plate')->ignore($this->carId),
            ],
            'type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, CarType::values())) {
                        $fail("The selected {$attribute} is invalid.");
                    }
                },
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:7'],
            'amount' => ['required', 'integer', 'min:0'],
            'cents' => ['nullable', 'integer', 'min:0', 'max:99'],
            'apk_expiry' => [
                'required',
                'date',
                "after_or_equal:$minDate",
                "before_or_equal:$maxDate",
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'license_plate.unique' => 'The license plate is already registered.',
            'license_plate.required' => 'The license plate is required.',
            'apk_expiry.after_or_equal' => 'The APK expiry date cannot be in the past.',
            'apk_expiry.before_or_equal' => 'The APK expiry date cannot be more than two years in the future.',
        ];
    }

    protected function resetInputFields(): void
    {
        $this->reset(['carId', 'name', 'license_plate', 'type', 'capacity', 'amount', 'cents', 'apk_expiry']);
    }

    public function openForCreate(): void
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function saveCar(): void
    {
        $this->license_plate = str_replace('-', '', strtoupper($this->license_plate));
        $this->validate();

        $totalCents = ((int) $this->amount) * 100 + ((int) $this->cents);

        if ($this->carId) {
            Car::findOrFail($this->carId)->update([
                'name' => $this->name,
                'license_plate' => $this->license_plate,
                'type' => $this->type,
                'capacity' => $this->capacity,
                'base_price_cents' => $totalCents,
                'apk_expiry' => $this->apk_expiry,
            ]);

            $this->dispatch('carUpdated');
        } else {
            Car::create([
                'name' => $this->name,
                'license_plate' => $this->license_plate,
                'type' => $this->type,
                'capacity' => $this->capacity,
                'base_price_cents' => $totalCents,
                'apk_expiry' => $this->apk_expiry,
            ]);

            $this->dispatch('carAdded');
        }

        $this->closeModal();
    }

    public function render(): View
    {
        return view('livewire.admin.car-modal');
    }
}
