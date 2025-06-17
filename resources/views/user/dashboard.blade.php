{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6 flex gap-8">

        <!-- Sidebar with categories -->
        <aside class="w-64 sticky top-6 h-[calc(100vh-48px)] overflow-y-auto bg-gray-50 p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Categories</h2>
            <ul>
                @foreach ($categories as $category)
                    <li class="mb-2">
                        <a href="{{ route('categories.show', $category->slug) }}" class="text-blue-600 hover:underline">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Main content with products -->
        <main class="flex-1">
            <h1 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="border rounded-lg p-4 shadow">
                        @if ($product->getFirstMediaUrl('images'))
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded mb-3">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-3 text-sm text-gray-500">No Image</div>
                        @endif

                        <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $product->description }}</p>
                        <p class="text-green-600 font-bold mt-2">R{{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="mt-3 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">View</a>
                    </div>
                @empty
                    <p>No products available.</p>
                @endforelse
            </div>
        </main>

    </div>
@endsection
