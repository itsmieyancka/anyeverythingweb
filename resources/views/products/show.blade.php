@extends('layouts.app')

@section('content')
    <style>
        .breadcrumb {
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .breadcrumb-item {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item:hover {
            color: #2563eb;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #9ca3af;
        }

        .variation-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .variation-option {
            position: relative;
            min-width: 40px;
            min-height: 40px;
        }

        .variation-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .variation-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .variation-label:hover {
            border-color: #93c5fd;
        }

        .variation-radio:checked + .variation-label {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px #2563eb;
        }

        .color-option .variation-label {
            border-radius: 50%;
            min-width: 40px;
            min-height: 40px;
        }

        .size-option .variation-label {
            min-width: 50px;
            padding: 0.75rem 1rem;
        }

        @media (max-width: 640px) {
            .variation-option {
                min-width: 35px;
                min-height: 35px;
            }

            .size-option .variation-label {
                min-width: 45px;
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

    <div class="max-w-6xl mx-auto px-4">
        {{-- Breadcrumbs --}}
        <nav class="breadcrumb">
            <!-- your breadcrumb markup -->
        </nav>

        <div class="flex flex-col md:flex-row gap-8">
            {{-- Product Image --}}
            <div class="w-full md:w-1/2">
                <img src="{{ $product->getFirstMediaUrl('images') }}"
                     alt="{{ $product->name }}"
                     class="w-full h-auto max-h-[400px] object-contain rounded shadow">
            </div>

            {{-- Product Info --}}
            <div class="w-full md:w-1/2 space-y-6">
                <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
                <p class="text-gray-600">{{ $product->description }}</p>

                @if ($product->vendor)
                    <p class="text-sm text-gray-500 mt-1">
                        Sold by: <strong>{{ $product->vendor->business_name ?? $product->vendor->name ?? 'Unknown Vendor' }}</strong>
                    </p>
                @endif

                {{-- Price --}}
                <p id="product-price" class="text-green-600 text-2xl font-semibold">
                    @if(empty($variationGroups) || count($variationGroups) === 0)
                        R{{ number_format($product->price, 2) }}
                    @else
                        R{{ number_format($variationSets[0]['price'] ?? $product->price, 2) }}
                    @endif
                </p>

                {{-- Stock --}}
                <p id="product-stock" class="text-sm text-gray-500">
                    Stock:
                    <span id="stock-count">
                        @if(empty($variationGroups) || count($variationGroups) === 0)
                            {{ $product->stock ?? 'N/A' }}
                        @else
                            {{ collect($variationSets)->sum('stock') }}
                        @endif
                    </span>
                </p>

                {{-- Simple Product Color --}}
                @if((empty($variationGroups) || count($variationGroups) === 0) && !empty($product->color))
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold mb-1">Color</h4>
                        <div class="flex gap-2 flex-wrap items-center">
                            <span
                                class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-xs"
                                title="{{ ucfirst($product->color) }}"
                                aria-label="Color: {{ ucfirst($product->color) }}"
                                style="background-color: {{ $product->color }};"
                            ></span>
                            <span class="ml-2 text-xs text-gray-600">{{ ucfirst($product->color) }}</span>
                        </div>
                    </div>
                @endif

                {{-- Variations Form --}}
                @if(!empty($variationGroups) && count($variationGroups) > 0)
                    <form method="POST" action="{{ route('cart.add', $product) }}" id="add-to-cart-form">
                        @csrf

                        @foreach ($variationGroups as $typeName => $options)
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold mb-3">{{ $typeName }}</h3>
                                <div class="variation-container">
                                    @foreach ($options as $option)
                                        <div class="variation-option {{ strtolower($typeName) }}-option">
                                            <input type="radio"
                                                   id="variation-{{ $typeName }}-{{ $option->id }}"
                                                   name="variation_option_ids[{{ $typeName }}]"
                                                   value="{{ $option->id }}"
                                                   class="variation-radio"
                                                   required
                                                   @if ($loop->first) checked @endif>

                                            <label for="variation-{{ $typeName }}-{{ $option->id }}"
                                                   class="variation-label"
                                                   data-type="{{ $typeName }}"
                                                   data-option-id="{{ $option->id }}"
                                                   @if(strtolower($typeName) === 'color')
                                                       style="background-color: {{ $option->value }};"
                                                   title="{{ $option->value }}"
                                                @endif
                                            >
                                                @if(strtolower($typeName) !== 'color')
                                                    {{ $option->value }}
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex space-x-4">
                            <button type="submit"
                                    class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="add-to-cart-btn">
                                Add to Cart
                            </button>
                            <a href="{{ route('products.index') }}"
                               class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg transition-all duration-200 text-center">
                                Back to Products
                            </a>
                        </div>
                    </form>
                    <div id="add-to-cart-message" class="mt-3 text-green-600 font-semibold hidden"></div>
                @else
                    {{-- For simple products, show Add to Cart --}}
                    <form method="POST" action="{{ route('cart.add', $product) }}" id="add-to-cart-form">
                        @csrf
                        <div class="flex space-x-4">
                            <button type="submit"
                                    class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="add-to-cart-btn"
                                    @if(($product->stock ?? 0) <= 0) disabled @endif>
                                {{ ($product->stock ?? 0) > 0 ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                            <a href="{{ route('products.index') }}"
                               class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg transition-all duration-200 text-center">
                                Back to Products
                            </a>
                        </div>
                    </form>
                    <div id="add-to-cart-message" class="mt-3 text-green-600 font-semibold hidden"></div>
                @endif
            </div>
        </div>
    </div>

    @if(!empty($variationGroups) && count($variationGroups) > 0)
        <script>
            const variationSets = @json($variationSets);

            function arraysEqual(a, b) {
                if (a.length !== b.length) return false;
                return [...a].sort().every((val, i) => val === [...b].sort()[i]);
            }

            function formatPrice(price) {
                return `R${parseFloat(price).toFixed(2)}`;
            }

            function updateProductInfo() {
                const selected = Array.from(document.querySelectorAll('input.variation-radio:checked'))
                    .map(i => parseInt(i.value))
                    .sort();

                const matchedSet = variationSets.find(set =>
                    arraysEqual(set.variation_option_ids, selected)
                );

                const priceEl = document.getElementById('product-price');
                const stockEl = document.getElementById('stock-count');
                const btn = document.getElementById('add-to-cart-btn');

                if (matchedSet) {
                    priceEl.textContent = formatPrice(matchedSet.price);
                    stockEl.textContent = matchedSet.stock;

                    const isOutOfStock = matchedSet.stock <= 0;
                    btn.disabled = isOutOfStock;
                    btn.textContent = isOutOfStock ? 'Out of Stock' : 'Add to Cart';
                } else {
                    priceEl.textContent = formatPrice({{ $product->price }});
                    stockEl.textContent = 'N/A';
                    btn.disabled = true;
                    btn.textContent = 'Select Options';
                }
            }

            // Add event listeners for variation changes
            document.querySelectorAll('input.variation-radio').forEach(radio =>
                radio.addEventListener('change', updateProductInfo)
            );

            document.addEventListener('DOMContentLoaded', () => {
                updateProductInfo();

                const form = document.getElementById('add-to-cart-form');
                const btn = document.getElementById('add-to-cart-btn');
                const messageDiv = document.getElementById('add-to-cart-message');

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    btn.disabled = true;
                    btn.textContent = 'Adding...';
                    messageDiv.classList.add('hidden');
                    messageDiv.textContent = '';

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();

                        // Show success message
                        messageDiv.textContent = 'Product added to cart!';
                        messageDiv.classList.remove('hidden');

                        // Optional: update cart count badge if you have one
                        const cartCountBadge = document.getElementById('cart-count-badge');
                        if (cartCountBadge && data.cartCount !== undefined) {
                            cartCountBadge.textContent = data.cartCount;
                        }
                        btn.textContent = 'Add to Cart';
                        btn.disabled = false;
                    } catch (error) {
                        console.error('Error adding to cart:', error);
                        messageDiv.textContent = 'Failed to add to cart. Please try again.';
                        messageDiv.classList.remove('hidden');
                        btn.textContent = 'Add to Cart';
                        btn.disabled = false;
                    }
                });
            });
        </script>
    @endif
@endsection
