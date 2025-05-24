@extends('layouts.app')

@section('content')

    <!-- Carousel Section -->
    <div class="carousel w-full mb-10">
        <div id="slide1" class="carousel-item relative w-full" style="max-height: 300px; overflow: hidden;">
            <img src="https://img.daisyui.com/images/stock/photo-1625726411847-8cbb60cc71e6.webp" class="w-full h-full object-cover" />
            <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                <a href="#slide4" class="btn btn-circle">❮</a>
                <a href="#slide2" class="btn btn-circle">❯</a>
            </div>
        </div>

        <div id="slide2" class="carousel-item relative w-full" style="max-height: 300px; overflow: hidden;">
            <img src="{{ asset('images/sale.png') }}" class="w-full h-full object-cover" alt="Sale Image" />

            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 bg-black bg-opacity-60 rounded p-4 flex gap-5 text-center text-white">
                <div class="flex flex-col items-center">
                    <span id="countdown-days" class="countdown font-mono text-3xl">40</span>
                    days
                </div>
                <div class="flex flex-col items-center">
                    <span id="countdown-hours" class="countdown font-mono text-3xl">0</span>
                    hours
                </div>
                <div class="flex flex-col items-center">
                    <span id="countdown-minutes" class="countdown font-mono text-3xl">0</span>
                    min
                </div>
                <div class="flex flex-col items-center">
                    <span id="countdown-seconds" class="countdown font-mono text-3xl">0</span>
                    sec
                </div>
            </div>

            <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                <a href="#slide1" class="btn btn-circle">❮</a>
                <a href="#slide3" class="btn btn-circle">❯</a>
            </div>
        </div>

        <div id="slide3" class="carousel-item relative w-full" style="max-height: 300px; overflow: hidden;">
            <img src="https://img.daisyui.com/images/stock/photo-1414694762283-acccc27bca85.webp" class="w-full h-full object-cover" />
            <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                <a href="#slide2" class="btn btn-circle">❮</a>
                <a href="#slide4" class="btn btn-circle">❯</a>
            </div>
        </div>

        <div id="slide4" class="carousel-item relative w-full" style="max-height: 300px; overflow: hidden;">
            <img src="https://img.daisyui.com/images/stock/photo-1665553365602-b2fb8e5d1707.webp" class="w-full h-full object-cover" />
            <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                <a href="#slide3" class="btn btn-circle">❮</a>
                <a href="#slide1" class="btn btn-circle">❯</a>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Featured Products</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>

    <!-- Countdown Timer Script -->
    <script>
        const saleDate = new Date();
        saleDate.setDate(saleDate.getDate() + 40);

        function updateCountdown() {
            const now = new Date();
            const diff = saleDate - now;

            if (diff <= 0) {
                document.getElementById('countdown-days').textContent = 0;
                document.getElementById('countdown-hours').textContent = 0;
                document.getElementById('countdown-minutes').textContent = 0;
                document.getElementById('countdown-seconds').textContent = 0;
                clearInterval(timerInterval);
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diff / (1000 * 60)) % 60);
            const seconds = Math.floor((diff / 1000) % 60);

            document.getElementById('countdown-days').textContent = days;
            document.getElementById('countdown-hours').textContent = hours;
            document.getElementById('countdown-minutes').textContent = minutes;
            document.getElementById('countdown-seconds').textContent = seconds;
        }

        updateCountdown();
        const timerInterval = setInterval(updateCountdown, 1000);
    </script>

@endsection


