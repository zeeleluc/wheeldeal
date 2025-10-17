<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-8">

        @if($step === 1)

            <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                Step 1: Dates & Passengers
            </h2>

            <div class="space-y-4">

                <input
                        type="date"
                        wire:model.live="start_date"
                        min="{{ now()->toDateString() }}"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />

                <input
                        type="date"
                        wire:model="end_date"
                        min="{{ $start_date ?? now()->toDateString() }}"
                        max="{{ $end_date_max ?? now()->addDays(config('car.rental.max_days'))->toDateString() }}"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />

                <input type="number" wire:model="passengers" min="1"
                       placeholder="Passengers"
                       class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                <button wire:click="nextStep"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                    Next
                </button>
            </div>

        @elseif($step === 2)

            <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                Step 2: Select a Car
            </h2>

            <div class="space-y-2">
                @forelse($availableCars as $car)
                    <label class="flex items-center border p-2 rounded cursor-pointer">
                        <input type="radio" wire:model="selectedCarId" value="{{ $car->id }}"
                               class="mr-2">
                        <span class="text-gray-800 dark:text-gray-100">
                            {{ $car->name }} ({{ $car->formatted_license_plate }}) - Capacity: {{ $car->capacity }}
                        </span>
                    </label>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">
                        No cars available for selected dates & passengers.
                    </p>
                @endforelse
            </div>

            <div class="mt-4 flex justify-between">
                <button wire:click="previousStep"
                        class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">
                    Back
                </button>

                @if($availableCars && $availableCars->isNotEmpty())
                    <button wire:click="nextStep"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                        Next
                    </button>
                @endif
            </div>

        @elseif($step === 3)

            <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                Step 3: Review & Quote
            </h2>

            <div class="space-y-2 text-gray-800 dark:text-gray-100">
                <p><strong>Car:</strong> {{ optional(\App\Models\Car::find($selectedCarId))->name }}</p>
                <p><strong>Start Date:</strong> {{ $start_date }}</p>
                <p><strong>End Date:</strong> {{ $end_date }}</p>
                <p><strong>Passengers:</strong> {{ $passengers }}</p>
                <p><strong>Total Price:</strong> €{{ number_format($quoteCents / 100, 2) }}</p>
                <p><strong>Daily Price:</strong> €{{ number_format($dailyPriceCents / 100, 2) }} / day</p>
                <p><strong>Duration:</strong> {{ $durationDays }} days</p>
            </div>

            <div class="mt-4 flex justify-between">
                <button wire:click="previousStep"
                        class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">
                    Back
                </button>
                <button wire:click="book"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg">
                    Confirm Reservation
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 text-red-500 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
</div>
