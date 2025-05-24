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

                    @if($product->variationTypes->count())
                        <p class="text-sm text-gray-500">
                            Variations:
                            @php
                                $variationSummary = $product->variationTypes->map(function ($type) {
                                    $options = $type->variationOptions->pluck('option')->join(', ');
                                    return "{$type->name}: {$options}";
                                })->join(' | ');
                            @endphp
                            {{ $variationSummary }}
                        </p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endsection

