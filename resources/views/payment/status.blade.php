@extends('layouts.app')

@section('content')
    <div class="w-full max-w-xl mx-auto mt-16 p-8 bg-white rounded-2xl shadow-lg text-center">
        <h2 class="text-3xl font-semibold mb-6">{{ __('Payment Status') }}</h2>

        <div class="flex flex-col items-center space-y-4">
            <div class="flex items-center space-x-3">
                <span class="{{ $statusEnum->circleClass() }} w-6 h-6"></span>
                <span class="text-{{ $statusEnum->color() }}-600 font-semibold text-lg">
                    {{ __($statusEnum->title()) }}
                </span>
            </div>

            <p class="text-gray-700">{{ __($statusEnum->message()) }}</p>

            <div class="flex flex-col items-center space-y-2 mt-6">
                <svg class="animate-spin h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <p class="text-sm text-gray-500">
                    {{ __('Resolving status and redirecting to your dashboard...') }}
                </p>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ $redirectUrl }}";
        }, 6000);
    </script>
@endsection
