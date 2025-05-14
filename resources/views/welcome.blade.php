<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to AnyEveryThing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') <!-- Only if using Vite -->
</head>
<body class="bg-gray-100 font-sans">

<!-- Navbar -->
<div class="navbar bg-base-100 shadow-sm px-4">
    <!-- Start: Menu Dropdown -->
    <div class="navbar-start">
        <div class="dropdown">
            <label tabindex="0" class="btn btn-ghost normal-case text-lg lg:hidden">Menu</label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-60">
                <li><a href="#">Clothing & Shoes</a></li>
                <li><a href="#">Homeware</a></li>
                <li><a href="#">Groceries & Household</a></li>
            </ul>
        </div>

        <!-- Large screen horizontal menu -->
        <ul class="menu menu-horizontal hidden lg:flex px-1">
            <li><a href="#">Clothing & Shoes</a></li>
            <li><a href="#">Homeware</a></li>
            <li><a href="#">Groceries & Household</a></li>
        </ul>
    </div>

    <!-- Center: Brand -->
    <div class="navbar-center">
        <a href="{{ url('/') }}" class="btn btn-ghost text-xl">AnyEveryThing</a>

    </div>

    <!-- End: Icons -->
    <div class="navbar-end space-x-2">
        <button class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <button class="btn btn-ghost btn-circle">
            <div class="indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="badge badge-xs badge-primary indicator-item"></span>
            </div>
        </button>
    </div>
</div>

<!-- Welcome Section -->
<div class="flex items-center justify-center min-h-[80vh] px-4">
    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl text-center max-w-md w-full">
        <h1 class="text-3xl font-bold text-blue-600 mb-4">Welcome to AnyEveryThing</h1>
        <p class="text-gray-700 mb-6">Your go-to platform for buying and selling everything!</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('login') }}" class="btn btn-primary w-full sm:w-auto">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline btn-primary w-full sm:w-auto">Register</a>
        </div>
    </div>
</div>

</body>
</html>

