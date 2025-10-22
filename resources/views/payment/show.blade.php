@extends('layouts.app')

@section('content')
    <div class="w-full max-w-xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-3xl font-semibold mb-6 text-center">{{ __('Payment Details') }}</h2>

        <div class="space-y-4">

            {{-- Basic reservation info --}}
            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Reservation ID:') }}</span>
                <span>#{{ $reservation->id }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Car:') }}</span>
                <span>{{ $reservation->car->name }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Pick-Up:') }}</span>
                <span>{{ $reservation->start_date->format('d-m-Y H:i') }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Return:') }}</span>
                <span>{{ $reservation->end_date->format('d-m-Y H:i') }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Total:') }}</span>
                <span class="font-bold">XCG {{ number_format($reservation->total_price_cents / 100, 2) }}</span>
            </div>

            {{-- Reservation Status --}}
            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Reservation Status:') }}</span>
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
                <div class="flex items-center justify-between">
                    <span class="font-medium">{{ __('Paid At:') }}</span>
                    <span>{{ $reservation->paid_at->format('d-m-Y H:i') }}</span>
                </div>
            @endif
        </div>

        {{-- Payment button --}}
        @can('pay', $reservation)
            <livewire:pay-button
                    :reservation="$reservation"
                    currency="XCG"
                    description="{{ __('Reservation #:id', ['id' => $reservation->id]) }}"
            />
        @endcan
    </div>
@endsection
