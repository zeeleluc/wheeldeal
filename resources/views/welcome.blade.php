@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-5xl font-bold mb-6">Make a deal, with {{ config('app.name') }}</h1>

        <a href="{{ route('reservation.create') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl text-xl transition duration-300"
        >
            {{ __('Start Your Reservation') }}
        </a>
    </div>
@endsection
