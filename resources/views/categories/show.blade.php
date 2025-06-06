@extends('layouts.app')

@section('content')

    <nav class="text-sm text-gray-600 mb-4">
        <a href="{{ route('home') }}" class="hover:underline">Home</a> >
        <a href="{{ route('departments.index') }}" class="hover:underline">Departments</a> >
        <span class="text-gray-800">{{ $category->name }}</span>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">{{ $category->name }}</h1>
        <p class="mb-4 text-gray-600">{{ $category->description }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($category->products as $product)
                <x-product-card :product="$product" />
            @empty
                <p>No products found in this category.</p>
            @endforelse
        </div>
    </div>
@endsection
