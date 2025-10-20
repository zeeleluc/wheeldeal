<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased flex flex-col min-h-screen">

<!-- Global Header -->
<header class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-xl font-semibold">
            {{ config('app.name', 'Laravel') }}
        </a>

        <div class="flex items-center space-x-3">
            @guest
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-800 transition-colors">
                    {{ __('Register') }}
                </a>
            @endguest

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
                       class="px-3 py-1.5 text-sm font-medium text-blue-600 bg-white dark:bg-gray-700 border border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                        {{ __('Fleet') }}
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                        {{ __('Logout') }}
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>

<!-- Draft Reservation Alert -->
<livewire:draft-reservation-bar />

<!-- Main Content -->
<main class="flex-grow">
    @yield('content')
</main>

<!-- Footer -->
<footer class="p-4 bg-blue-600 text-white text-center text-sm">
    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved.') }}
</footer>

@livewireScripts
</body>
</html>
