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

            {{-- STATUS --}}
            <div class="flex items-center justify-between">
                <span class="font-medium">{{ __('Status:') }}</span>
                <div class="flex items-center space-x-2">
                    @if($reservation->status === \App\Enums\ReservationType::DRAFT)
                        <span class="w-4 h-4 bg-gray-400 rounded-full"></span>
                        <span class="text-gray-600 font-semibold">{{ $reservation->status->title() }}</span>

                    @elseif($reservation->status === \App\Enums\ReservationType::ABORTED)
                        <span class="w-4 h-4 bg-gray-400 rounded-full"></span>
                        <span class="text-gray-600 font-semibold">{{ $reservation->status->title() }}</span>

                    @elseif($reservation->status === \App\Enums\ReservationType::CANCELLED)
                        <span class="w-4 h-4 bg-red-500 rounded-full"></span>
                        <span class="text-red-600 font-semibold">{{ $reservation->status->title() }}</span>

                    @elseif($reservation->status === \App\Enums\ReservationType::PENDING_PAYMENT)
                        <span class="w-4 h-4 border-4 border-orange-400 border-t-orange-600 rounded-full animate-spin"></span>
                        <span class="text-orange-600 font-semibold">{{ $reservation->status->title() }}</span>

                    @elseif($reservation->status === \App\Enums\ReservationType::PAID)
                        <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                        <span class="text-green-600 font-semibold">{{ $reservation->status->title() }}</span>

                    @else
                        <span class="w-4 h-4 bg-gray-300 rounded-full"></span>
                        <span class="text-gray-500 font-semibold">{{ __('Unknown') }}</span>
                    @endif
                </div>
            </div>

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
