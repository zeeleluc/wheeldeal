<div>
    {{-- ADD NEW BUTTON --}}
    <button wire:click="openForCreate"
            class="mb-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
        Add New Car
    </button>

    {{-- MODAL --}}
    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="w-full max-w-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-8">

                {{-- MODAL HEADER --}}
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6 text-center">
                    {{ $carId ? 'Edit Car' : 'Add New Car' }}
                </h2>

                {{-- FORM --}}
                <form wire:submit.prevent="saveCar" class="space-y-4">

                    {{-- NAME --}}
                    <div>
                        <input type="text"
                               wire:model.defer="name"
                               placeholder="Name"
                               class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- LICENSE PLATE --}}
                    <div>
                        <div x-data="licensePlateInput(@entangle('license_plate'))"
                             x-init="init()"
                             x-effect="formatPlate()">
                            <input type="text"
                                   x-ref="input"
                                   x-model="value"
                                   @input="formatPlate()"
                                   @keydown.backspace.prevent="handleBackspace($event)"
                                   class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                   placeholder="License Plate">
                        </div>
                        @error('license_plate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- TYPE --}}
                    <div>
                        <select wire:model.defer="type"
                                class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Select Type</option>
                            @foreach(\App\Enums\CarType::cases() as $carType)
                                <option value="{{ $carType->value }}">{{ $carType->title() }}</option>
                            @endforeach
                        </select>
                        @error('type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- CAPACITY --}}
                    <div>
                        <input type="number"
                               wire:model.defer="capacity"
                               placeholder="Capacity"
                               class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('capacity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- BASE PRICE --}}
                    <div>
                        <input type="number"
                               wire:model.defer="base_price_cents"
                               placeholder="Base Price (cents)"
                               class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('base_price_cents')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- APK EXPIRY --}}
                    <div>
                        @php
                            $minDate = now()->addDays(config('car.apk.min_days'))->toDateString();
                            $maxDate = now()->addYears(config('car.apk.max_years'))->toDateString();
                        @endphp

                        <input type="date"
                               wire:model.defer="apk_expiry"
                               min="{{ $minDate }}"
                               max="{{ $maxDate }}"
                               class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('apk_expiry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- FORM BUTTONS --}}
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>