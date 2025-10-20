<div class="flex justify-center items-center min-h-[70vh] px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-xl p-10">

        @if($step === 1)
            <h2 class="text-3xl font-semibold text-center mb-8 text-gray-800 dark:text-gray-100">
                {{ __('Dates & Passengers') }}
            </h2>

            <div class="space-y-5 mt-6">
                @if($alternativeDates && $alternativeDates->isNotEmpty())
                    <div class="relative">
                        <input type="number" wire:model="passengers" min="1"
                               disabled
                               class="w-full border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg p-3 cursor-not-allowed"
                               title="{{ __('Change passengers to refresh alternative dates') }}">

                        <button wire:click="clearAlternativeDates"
                                class="absolute top-0 right-0 mt-1 mr-1 px-2 py-1 bg-yellow-400 text-white rounded text-xs hover:bg-yellow-500 transition-all">
                            {{ __('Change') }}
                        </button>
                    </div>

                    <div class="mt-4 p-4 border border-yellow-300 bg-yellow-50 rounded-lg">
                        <h3 class="text-lg font-medium text-yellow-800 mb-2">{{ __('No cars available, try alternative dates:') }}</h3>
                        @foreach($alternativeDates as $range)
                            <button
                                    wire:click="selectAlternativeDates('{{ $range['start_date'] }}', '{{ $range['end_date'] }}')"
                                    class="w-full mb-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-md transition-all">
                                {{ $range['start_date'] }} → {{ $range['end_date'] }}
                            </button>
                        @endforeach
                    </div>
                @else
                    <input type="number" wire:model="passengers" min="1"
                           placeholder="{{ __('Passengers') }}"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 focus:outline-none">

                    <input
                            type="date"
                            wire:model.live="start_date"
                            min="{{ now()->toDateString() }}"
                            class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 focus:outline-none"/>

                    <input
                            type="date"
                            wire:model="end_date"
                            min="{{ $start_date ?? now()->toDateString() }}"
                            max="{{ $end_date_max ?? now()->addDays(config('car.rental.max_days'))->toDateString() }}"
                            class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 focus:outline-none"/>

                    <button wire:click="nextStep"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg transition-all mt-4">
                        {{ __('Next') }}
                    </button>
                @endif
            </div>

        @elseif($step === 2)
            <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                {{ __('Select a Car') }}
            </h2>

            <p class="text-sm text-gray-600 mb-4 text-center">
                <span class="font-medium">{{ $start_date }} → {{ $end_date }}</span>
            </p>

            <div class="space-y-3">
                @foreach($availableCars as $car)
                    <div
                            wire:key="car-{{ $car['id'] }}"
                            wire:click="$set('selectedCarId', {{ $car['id'] }})"
                            @class([
                                'cursor-pointer p-4 rounded-xl border transition-all mb-3 shadow-sm hover:shadow-md',
                                'border-blue-600 bg-blue-50 dark:bg-gray-700' => $selectedCarId === $car['id'],
                                'border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800' => $selectedCarId !== $car['id'],
                            ])
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-gray-800 dark:text-gray-100 font-semibold text-lg">
                                    {{ $car['name'] }}
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm">
                                    {{ __('Max.') }} {{ $car['capacity'] }} {{ __('passengers') }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-800 dark:text-gray-100 font-bold text-lg">
                                    XCG {{ number_format($car['dailyPriceCents'] / 100, 2) }}/{{ __('day') }}
                                </span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                    {{ __('Total:') }} XCG {{ number_format($car['totalPriceCents'] / 100, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-between">
                <button wire:click="previousStep"
                        class="px-6 py-2 border rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    {{ __('Back') }}
                </button>
                @if($availableCars && $availableCars->isNotEmpty())
                    <button wire:click="nextStep"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all">
                        {{ __('Next') }}
                    </button>
                @endif
            </div>

        @elseif($step === 3)
            <div class="space-y-4">
                <h2 class="text-3xl font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">
                    {{ __('Review & Quote') }}
                </h2>

                <div class="space-y-2 text-gray-800 dark:text-gray-100">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Car:') }}</span>
                        <span>{{ optional(\App\Models\Car::find($selectedCarId))->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Pick-Up Date:') }}</span>
                        <span>{{ $start_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Return Date:') }}</span>
                        <span>{{ $end_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Duration:') }}</span>
                        <span>{{ $durationDays }} {{ __('days') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Passengers:') }}</span>
                        <span>{{ $passengers }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Daily Price:') }}</span>
                        <span>XCG {{ number_format($dailyPriceCents / 100, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">{{ __('Total Price:') }}</span>
                        <span class="font-bold">XCG {{ number_format($quoteCents / 100, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <button wire:click="previousStep"
                            class="px-6 py-2 border rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                        {{ __('Back') }}
                    </button>
                    <button wire:click="book"
                            class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all">
                        {{ __('Confirm Reservation') }}
                    </button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 text-red-500 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 text-red-500 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

    </div>
</div>
