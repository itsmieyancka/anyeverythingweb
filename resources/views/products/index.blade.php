@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Product List</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="block bg-white rounded shadow hover:shadow-lg transition duration-300 p-4">

                    @if($product->hasMedia('images'))
                        <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover rounded mb-4">
                    @endif

                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>

                    <p class="text-gray-600 mb-1">R{{ number_format($product->price, 2) }}</p>

                    <p class="text-sm text-gray-500 mb-1">
                        Sold by:
                        <span class="font-medium">
                            {{ $product->vendor->business_name ?? 'Unknown Vendor' }}
                        </span>
                    </p>

                    {{-- Color Circles for both simple and variable products --}}
                    @php
                        // Find the 'color' variation type if exists
                        $colorVariationType = $product->variationTypes->first(function ($type) {
                            return strtolower($type->name) === 'color';
                        });
                    @endphp

                    @if($colorVariationType && $colorVariationType->variationOptions->count())
                        {{-- Show color circles from variation options --}}
                        <div class="flex gap-2 mt-2">
                            @foreach ($colorVariationType->variationOptions as $option)
                                <span
                                    class="w-6 h-6 rounded-full border-2 border-gray-300"
                                    title="{{ $option->value }}"
                                    style="background-color: {{ $option->value }};"
                                    aria-label="Color: {{ $option->value }}"
                                ></span>
                            @endforeach
                        </div>
                    @elseif(!empty($product->color))
                        {{-- Show single color circle for simple product --}}
                        <div class="flex gap-2 mt-2">
                            <span
                                class="w-6 h-6 rounded-full border-2 border-gray-300"
                                title="{{ ucfirst($product->color) }}"
                                style="background-color: {{ $product->color }};"
                                aria-label="Color: {{ ucfirst($product->color) }}"
                            ></span>
                        </div>
                    @endif

                </a>
            @endforeach
        </div>
    </div>
@endsection
