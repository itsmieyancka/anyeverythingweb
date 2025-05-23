<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'AnyEveryThing') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen relative">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Fixed Cart Icon Top Right -->
    <a href="{{ route('cart.index') }}"
       class="fixed top-4 right-4 z-50 bg-blue-600 hover:bg-blue-700 rounded-full shadow-lg p-3 transition"
       title="View Cart">
        <div class="relative">
            <!-- Shopping Cart Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-1.4 7M17 13l1.4 7M6 20h12" />
            </svg>
            <!-- Cart Count Badge -->
            @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
            @if ($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-xs text-white rounded-full px-2 py-0.5">
                    {{ $cartCount }}
                </span>
            @endif
        </div>
    </a>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
</div>

@livewireScripts
@yield('scripts')
</body>
</html>

