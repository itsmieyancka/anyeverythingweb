{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6 flex gap-6">
        {{-- Small left sidebar with categories and spend range --}}
        <div class="w-40 flex flex-col space-y-6">
            {{-- Shop by Categories --}}
            <div>
                <h2 class="text-sm font-semibold mb-2">Shop by Categories</h2>
                <nav class="flex flex-col space-y-1 text-xs">
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.index') }}#{{ \Illuminate\Support\Str::slug($category->name) }}"
                           class="text-blue-600 hover:underline truncate"
                           title="{{ $category->name }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Spend Range Slider --}}
            <div class="flex flex-col items-center space-y-2">
                <label for="spendRange" class="text-sm font-medium">Spend Range</label>
                <input
                    type="range"
                    id="spendRange"
                    min="50"
                    max="1000"
                    value="50"
                    step="10"
                    class="range w-full"
                    oninput="document.getElementById('rangeValue').textContent = this.value"
                    title="Adjust your spend range"
                />
                <div class="text-sm font-semibold text-green-600">R<span id="rangeValue">50</span></div>
            </div>
        </div>

        {{-- Main content with products --}}
        <div class="flex-1">
            <h1 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="border rounded-lg p-4 shadow">
                        {{-- Product Image --}}
                        @if ($product->getFirstMediaUrl('images'))
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded mb-3">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-3 text-sm text-gray-500">No Image</div>
                        @endif

                        {{-- Product Info --}}
                        <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $product->description }}</p>
                        <p class="text-green-600 font-bold mt-2">R{{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="mt-3 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">View</a>
                    </div>
                @empty
                    <p>No products available.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
