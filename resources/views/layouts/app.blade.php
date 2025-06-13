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

    <!-- Alpine.js for toggle -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Custom CSS for transitions -->
    <style>
        .search-transition {
            transition: all 0.2s ease-in-out;
        }
        .cart-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen relative">
    <!-- DaisyUI Navbar -->
    <div class="navbar bg-base-100 shadow-sm relative z-20">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-30 mt-3 w-52 p-2 shadow-lg">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Homepage</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Portfolio</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">About</a></li>
                </ul>
            </div>
        </div>

        <div class="navbar-center">
            <a href="{{ route('home') }}" class="btn btn-ghost normal-case text-xl">AnyEveryThing</a>
        </div>

        <div class="navbar-end flex items-center space-x-2">
            <!-- Search Toggle with Alpine.js -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="btn btn-ghost btn-circle" aria-label="Toggle Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Search Input -->
                <div x-show="open"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded shadow-lg p-2 z-30 search-transition"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Search..."
                            class="input input-bordered w-full pr-10"
                            autofocus
                            @keydown.escape.window="open = false"
                        />
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Button -->
            <button class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                </div>
            </button>

            <!-- Cart Button -->
            <a href="{{ route('cart.index') }}" class="btn btn-ghost btn-circle relative" title="View Cart">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-1.4 7M17 13l1.4 7M6 20h12" />
                </svg>
                @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                @if ($cartCount > 0)
                    <span class="badge badge-xs badge-primary indicator-item absolute top-0 right-0 -mt-1 -mr-1">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- Login and Register Buttons -->
            @guest
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm normal-case">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm normal-case">Register</a>
            @else
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-sm normal-case">
                        {{ Auth::user()->name }}
                    </label>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest

        </div>
    </div>

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






<footer class="footer sm:footer-horizontal bg-neutral text-neutral-content p-10">
    <nav>
        <h6 class="footer-title">Services</h6>
        <a class="link link-hover">Branding</a>
        <a class="link link-hover">Design</a>
        <a class="link link-hover">Marketing</a>
        <a class="link link-hover">Advertisement</a>
    </nav>
    <nav>
        <h6 class="footer-title">Company</h6>
        <a class="link link-hover">About us</a>
        <a class="link link-hover">Contact</a>
        <a class="link link-hover">Jobs</a>
        <a class="link link-hover">Press kit</a>
    </nav>
    <nav>
        <h6 class="footer-title">Legal</h6>
        <a class="link link-hover">Terms of use</a>
        <a class="link link-hover">Privacy policy</a>
        <a class="link link-hover">Cookie policy</a>
    </nav>
</footer>



@livewireScripts
@yield('scripts')
</body>
</html>

