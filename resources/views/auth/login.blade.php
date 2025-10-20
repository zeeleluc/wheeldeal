@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[60vh] px-4">
        <div class="w-full max-w-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-xl p-8">
            <h1 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100">
                {{ __('Login') }}
            </h1>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 text-red-500 text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Email') }}
                    </label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-600 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Password') }}
                    </label>
                    <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-600 focus:outline-none"
                    >
                </div>

                <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl shadow-md transition-all"
                >
                    {{ __('Log in') }}
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                    {{ __('Click here to register') }}
                </a>
            </div>
        </div>
    </div>
@endsection
