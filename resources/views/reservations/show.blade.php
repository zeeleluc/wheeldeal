@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[60vh]">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                {{ __('Reservation Details') }}
            </h1>

            <div class="space-y-4 text-gray-800 dark:text-gray-100">
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Car') }}:</span>
                    <span>{{ $reservation->car->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('License Plate') }}:</span>
                    <span>{{ $reservation->car->formatted_license_plate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Pick-Up Date') }}:</span>
                    <span>{{ $reservation->start_date->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Return Date') }}:</span>
                    <span>{{ $reservation->end_date->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Duration') }}:</span>
                    <span>{{ $reservation->start_date->diffInDays($reservation->end_date) + 1 }} {{ __('days') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Passengers') }}:</span>
                    <span>{{ $reservation->passengers }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">{{ __('Daily Price') }}:</span>
                    <span>XCG {{ number_format($reservation->total_price_cents / ($reservation->start_date->diffInDays($reservation->end_date) + 1) / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center font-bold">
                    <span>{{ __('Total Price') }}:</span>
                    <span>XCG {{ number_format($reservation->total_price_cents / 100, 2) }}</span>
                </div>

                {{-- Reservation Status --}}
                <div class="flex justify-between items-center">
                    <span class="font-medium">{{ __('Reservation Status') }}:</span>
                    <div class="flex items-center space-x-2">
                        <span class="w-4 h-4 {{ $reservation->status->circleClass() }} rounded-full"></span>
                        <span class="{{ $reservation->status->textClass() }} font-semibold">
                            {{ __($reservation->status->title()) }}
                        </span>
                    </div>
                </div>

                {{-- Payment Status --}}
                @if ($payment = $reservation->latestPayment())
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ __('Payment Status:') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-4 h-4 {{ $payment->status->circleClass() }} rounded-full"></span>
                            <span class="{{ $payment->status->textClass() }} font-semibold">
                            {{ __($payment->status->title()) }}
                        </span>
                        </div>
                    </div>
                @endif

                @if($reservation->paid_at)
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('Paid At') }}:</span>
                        <span>{{ $reservation->paid_at->format('d-m-Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
