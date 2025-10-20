<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Car Fleet') }}</h1>
        @livewire('admin.car-modal')
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">{{ __('Name') }}</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">{{ __('License Plate') }}</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">{{ __('Type') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ __('Capacity') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ __('Base Price') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ __('APK Expiry') }}</th>
                <th class="px-2 py-3 text-right text-gray-700 dark:text-gray-300"></th>
            </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($cars as $car)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $car->name }}</td>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-mono">{{ $car->formatted_license_plate }}</td>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $car->type->title() }}</td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ $car->capacity }}</td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">XCG {{ number_format($car->base_price_cents / 100, 2) }}</td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ $car->apk_expiry->format('d-m-Y') }}</td>
                    <td class="px-2 py-3 text-right">
                        <button wire:click="openModal({{ $car->id }})"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                            {{ __('Edit') }}
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
