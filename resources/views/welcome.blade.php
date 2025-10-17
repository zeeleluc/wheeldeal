@extends('layouts.app')

@section('content')
    <div class="text-center py-20">
        <h1 class="text-5xl font-bold mb-6">Welcome to {{ config('app.name') }}</h1>
        <p class="text-gray-600 mb-8">
            <livewire:reservation-form />
        </p>
    </div>
@endsection
