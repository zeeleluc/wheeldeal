<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="antialiased min-h-screen flex flex-col">

<!-- Global Header -->
<header class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-xl font-semibold">
            {{ config('app.name', 'Laravel') }}
        </a>

        <div class="flex items-center space-x-3">

            {{-- Guest Menu --}}
            @guest
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors">
                    Register
                </a>
            @endguest

            {{-- Authenticated Menu --}}
            @auth
                <a href="{{ route('user.show', auth()->user()) }}">
                    <div class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <span class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                            👋 {{ auth()->user()->name }}
                        </span>
                    </div>
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.cars') }}"
                       class="px-3 py-1.5 text-sm font-medium text-indigo-600 bg-white dark:bg-gray-700 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">
                        Fleet
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            @endauth

        </div>

    </div>
</header>

<!-- Draft Reservation Alert -->
<livewire:draft-reservation-bar />

<!-- Main Content -->
@yield('content')

<!-- Footer -->
<footer class="p-4 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-600">
    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
</footer>

<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>
