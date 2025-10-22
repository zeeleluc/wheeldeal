<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Your Wheel Deals') }}</h1>
        <x-reservation-button title="{{ __('New Reservation') }}"/>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 min-w-[180px]">{{ __('Car') }}</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300">{{ __('Status') }}</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 min-w-[130px]">{{ __('Start Date') }}</th>
                <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 min-w-[130px]">{{ __('End Date') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ __('Passengers') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300 min-w-[180px]">{{ __('Total Price') }}</th>
                <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300 min-w-[180px]"></th>
            </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($reservations as $reservation)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">{{ $reservation->car->name }}</td>

                    <td class="px-4 py-3 flex items-center space-x-2">
                        <span class="w-4 h-4 {{ $reservation->status->circleClass() }} rounded-full"></span>
                        <span class="{{ $reservation->status->textClass() }} font-semibold">
                            {{ __($reservation->status->title()) }}
                        </span>
                    </td>

                    <td class="px-4 py-3">{{ $reservation->start_date->format('d-m-Y') }}</td>
                    <td class="px-4 py-3">{{ $reservation->end_date->format('d-m-Y') }}</td>
                    <td class="px-4 py-3 text-right">{{ $reservation->passengers }}</td>
                    <td class="px-4 py-3 text-right">
                        XCG {{ number_format($reservation->total_price_cents / 100, 2) }}</td>

                    <td class="px-4 py-3 text-right space-x-2">
                        @can('pay', $reservation)
                            <a href="{{ route('payment.show', $reservation) }}"
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
                                {{ __('Pay') }}
                                @if ($reservation->payments()->count())
                                    (Try Again)
                                @endif
                            </a>
                        @else
                            <a href="{{ route('reservations.show', $reservation) }}"
                               class="px-3 py-1 bg-gray-400 hover:bg-gray-500 text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
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
