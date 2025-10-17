@extends('layouts.app')

@section('content')

    <div class="flex justify-center items-center min-h-[60vh]"> <div class="w-full max-w-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-8"> <h1 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-gray-100"> Register </h1>
            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 text-red-500 text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Name
                    </label>
                    <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                    </label>
                    <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="mt-1 w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors"
                >
                    Register
                </button>
            </form>

            <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-6">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
            </p>
        </div>

    </div>

@endsection