@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[60vh]">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-8">
            <h1 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                Reservation Details
            </h1>

            <div class="space-y-4 text-gray-800 dark:text-gray-100">
                <p><strong>Car:</strong> {{ $reservation->car->name }} ({{ $reservation->car->formatted_license_plate }})</p>
                <p><strong>Passengers:</strong> {{ $reservation->passengers }}</p>
                <p><strong>Start Date:</strong> {{ $reservation->start_date->format('d-m-Y') }}</p>
                <p><strong>End Date:</strong> {{ $reservation->end_date->format('d-m-Y') }}</p>
                <p><strong>Duration:</strong> {{ $reservation->start_date->diffInDays($reservation->end_date) + 1 }} days</p>
                <p><strong>Total Price:</strong> €{{ number_format($reservation->total_price_cents / 100, 2) }}</p>
                <p><strong>Daily Price:</strong> €{{ number_format($reservation->total_price_cents / ($reservation->start_date->diffInDays($reservation->end_date) + 1) / 100, 2) }} / day</p>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('welcome') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
