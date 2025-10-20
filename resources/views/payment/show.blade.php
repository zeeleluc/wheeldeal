@extends('layouts.app')

@section('content')
    <div class="w-full max-w-xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-3xl font-semibold mb-6 text-center">Payment Details</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="font-medium">Reservation ID:</span>
                <span>#{{ $reservation->id }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-medium">Car:</span>
                <span>{{ $reservation->car->name }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-medium">Pick-Up:</span>
                <span>{{ $reservation->start_date->format('d-m-Y H:i') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-medium">Return:</span>
                <span>{{ $reservation->end_date->format('d-m-Y H:i') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-medium">Total:</span>
                <span class="font-bold">XCG {{ number_format($reservation->total_price_cents / 100, 2) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-medium">Status:</span>
                <div class="flex items-center space-x-2">
                    @if($reservation->paid_at)
                        <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                        <span class="text-green-600 font-semibold">Paid</span>
                    @else
                        <span class="w-4 h-4 border-4 border-orange-400 border-t-orange-600 rounded-full animate-spin"></span>
                        <span class="text-orange-600 font-semibold">Pending</span>
                    @endif
                </div>
            </div>
            @if($reservation->paid_at)
                <div class="flex items-center justify-between">
                    <span class="font-medium">Paid At:</span>
                    <span>{{ $reservation->paid_at->format('d-m-Y H:i') }}</span>
                </div>
            @endif
        </div>

        @can('pay', $reservation)
            <livewire:pay-button
                    :reservation="$reservation"
                    currency="XCG"
                    description="Reservation #{{ $reservation->id }}"
            />
        @endcan

    </div>
@endsection
