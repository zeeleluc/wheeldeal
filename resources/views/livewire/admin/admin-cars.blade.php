<div>
    @livewire('admin.car-modal')

    <div class="overflow-x-auto mt-6">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">Name</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">License Plate</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">Type</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-right">Capacity</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-right">Base Price</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-right">APK Expiry</th>
                <th class="p-2 text-right"></th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($cars as $car)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->name }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 font-mono">{{ $car->formatted_license_plate }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->type->title() }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 text-right">{{ $car->capacity }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 text-right">${{ number_format($car->base_price_cents / 100, 2) }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 text-right">{{ $car->apk_expiry->format('d-m-Y') }}</td>
                    <td class="p-2 text-right">
                        <button wire:click="openModal({{ $car->id }})"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
