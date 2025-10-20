@extends('layouts.app')

@section('content')
    <livewire:rental-frequency-alert/>
    <div class="container mx-auto p-6">
        <livewire:user-reservations-table/>
    </div>
@endsection
