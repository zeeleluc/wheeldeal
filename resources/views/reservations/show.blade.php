@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[60vh]">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                Reservation Details
            </h1>

            <div class="space-y-3 text-gray-800 dark:text-gray-100">
                <div class="flex justify-between">
                    <span class="font-medium">Car:</span>
                    <span>{{ $reservation->car->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">License Plate:</span>
                    <span>{{ $reservation->car->formatted_license_plate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Pick-Up Date:</span>
                    <span>{{ $reservation->start_date->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Return Date:</span>
                    <span>{{ $reservation->end_date->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Duration:</span>
                    <span>{{ $reservation->start_date->diffInDays($reservation->end_date) + 1 }} days</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Passengers:</span>
                    <span>{{ $reservation->passengers }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Daily Price:</span>
                    <span>${{ number_format($reservation->total_price_cents / ($reservation->start_date->diffInDays($reservation->end_date) + 1) / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">Total Price:</span>
                    <span class="font-bold">${{ number_format($reservation->total_price_cents / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-medium">Status:</span>
                    <div class="flex items-center space-x-2">
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
                    </div>
                </div>
                @if($reservation->paid_at)
                    <div class="flex items-center justify-between">
                        <span class="font-medium">Paid At:</span>
                        <span>{{ $reservation->paid_at->format('d-m-Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
