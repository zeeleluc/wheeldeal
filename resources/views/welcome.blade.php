@extends('layouts.app')

@section('content')
    <main class="flex-grow flex flex-col justify-center items-center bg-gradient-to-r from-blue-100 to-blue-200 px-6 min-h-[calc(100vh-140px)]">
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-gray-800 mb-8 text-center leading-tight">
            {{ __('Make a deal, with') }} <span class="text-blue-600">{{ config('app.name') }}</span>
        </h1>

        <p class="text-lg sm:text-xl text-gray-700 mb-12 text-center max-w-xl">
            {{ __('Reserve your spot quickly and securely. It only takes a few steps to start your journey with us!') }}
        </p>

        <a href="{{ route('reservation.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-10 rounded-2xl text-xl shadow-lg transform hover:scale-105 transition duration-300"
        >
            {{ __('Start Your Reservation') }}
        </a>
    </main>
@endsection
