@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-3xl font-semibold mb-6 text-center">{{ __('Payment Status') }}</h2>

        <div class="flex items-center space-x-4 justify-center">
            <span class="{{ $statusEnum->circleClass() }} w-6 h-6"></span>
            <span class="text-{{ $statusEnum->color() }}-600 font-semibold text-lg">{{ __($statusEnum->label()) }}</span>
        </div>

        <p class="mt-4 text-center text-gray-700">
            {{ __($statusEnum->message()) }}
        </p>
    </div>
@endsection
