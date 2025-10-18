<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('Your Wheel Deals') }}</h1>
        <x-reservation-button title="{{ __('New Reservation') }}" />
    </div>
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">Car</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">Status</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">Start Date</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">End Date</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-right">Passengers</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-right">Total Price</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300"></th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($reservations as $reservation)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $reservation->car->name }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                        @switch($reservation->status)
                            @case(\App\Enums\ReservationType::DRAFT)
                                <span class="w-4 h-4 bg-gray-400 rounded-full"></span>
                                <span class="text-gray-600 font-semibold">Draft</span>
                                @break

                            @case(\App\Enums\ReservationType::PENDING_PAYMENT)
                                <span class="w-4 h-4 border-4 border-orange-400 border-t-orange-600 rounded-full animate-spin"></span>
                                <span class="text-orange-600 font-semibold">Pending Payment</span>
                                @break

                            @case(\App\Enums\ReservationType::PAID)
                                <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                                <span class="text-green-600 font-semibold">Paid</span>
                                @break

                            @case(\App\Enums\ReservationType::CANCELLED)
                                <span class="w-4 h-4 bg-red-500 rounded-full"></span>
                                <span class="text-red-600 font-semibold">Cancelled</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $reservation->start_date->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $reservation->end_date->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 text-right">{{ $reservation->passengers }}</td>
                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 text-right">${{ number_format($reservation->total_price_cents / 100, 2) }}</td>
                    <td class="px-4 py-2 text-right">
                        @can('pay', $reservation)
                            <a href="{{ route('payment.show', $reservation) }}"
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ __('Pay') }}
                            </a>
                        @else
                            <a href="{{ route('reservations.show', $reservation) }}"
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ __('View') }}
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
