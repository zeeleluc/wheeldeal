@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-5xl font-bold mb-6">Make a deal with {{ config('app.name') }}</h1>
        <livewire:reservation-form />
    </div>
@endsection
