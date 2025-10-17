<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col">

<!-- Global Header -->
<header class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-xl font-semibold">
            {{ config('app.name', 'Laravel') }}
        </a>

        {{-- Auth Links --}}
        <div>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                            type="submit"
                            class="text-sm text-red-600 hover:underline font-medium"
                    >
                        Logout
                    </button>
                </form>
            @else
                <a
                        href="{{ route('login') }}"
                        class="text-sm text-indigo-600 hover:underline font-medium"
                >
                    Admin Login
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="flex-1 container mx-auto p-6">
    @yield('content')
</main>

<!-- Footer -->
<footer class="p-4 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-600">
    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
</footer>

</body>
</html>
