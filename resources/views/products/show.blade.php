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
                            Select all options
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
                    {{-- Simple Product Form --}}
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

                {{-- Rating Form --}}
                <div class="mt-10">
                    <h2 class="text-lg font-semibold mb-2">Rate this Product</h2>

                    @if (auth()->check() && auth()->user()->hasPurchased($product))
                        <form method="POST" action="{{ route('ratings.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="rating mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}"
                                           class="mask mask-star-2 bg-green-500"
                                           aria-label="{{ $i }} star"
                                           @if(old('rating') == $i) checked @endif />
                                @endfor
                            </div>

                            <textarea name="review" class="textarea textarea-bordered w-full" placeholder="Optional review...">{{ old('review') }}</textarea>

                            <button class="btn btn-success mt-2">Submit Rating</button>
                        </form>
                    @elseif(auth()->check())
                        <div class="alert alert-warning mt-4">
                            You can only rate this product if you’ve purchased it.
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            <a href="{{ route('login') }}" class="link">Log in</a> to rate this product.
                        </div>
                    @endif
                </div>
                {{-- Display user's own rating if exists --}}
                @if($userRating)
                    <div class="mt-4 p-4 border rounded bg-green-50">
                        <p class="font-semibold text-sm">You rated this product:</p>
                        <div class="rating mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio"
                                       class="mask mask-star-2 bg-green-500"
                                       disabled
                                       @if($userRating->rating == $i) checked @endif />
                            @endfor
                        </div>
                        @if($userRating->review)
                            <p class="text-sm mt-2 italic text-gray-700">"{{ $userRating->review }}"</p>
                        @endif
                    </div>
                @endif

                {{-- Display all other ratings --}}
                @if($product->ratings->count())
                    <div class="mt-6">
                        <h3 class="text-md font-semibold mb-2">What others are saying:</h3>
                        @foreach($product->ratings->where('user_id', '!=', auth()->id()) as $rating)
                            <div class="mb-4 p-4 border rounded bg-white shadow-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-bold">{{ $rating->user->name }}</span>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio"
                                                   class="mask mask-star-2 bg-yellow-400"
                                                   disabled
                                                   @if($rating->rating == $i) checked @endif />
                                        @endfor
                                    </div>
                                </div>
                                @if($rating->review)
                                    <p class="text-sm italic text-gray-700">"{{ $rating->review }}"</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>


    @if(!empty($variationGroups) && count($variationGroups) > 0)
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const variationSets = @json($variationSets);

                            function arraysEqual(a, b) {
                                if (!a || !b) return false;
                                if (a.length !== b.length) return false;
                                const sortedA = [...a].sort((x, y) => x - y);
                                const sortedB = [...b].sort((x, y) => x - y);
                                return sortedA.every((val, i) => val === sortedB[i]);
                            }

                            function formatPrice(price) {
                                return 'R' + parseFloat(price).toFixed(2);
                            }

                            function updateProductInfo() {
                                const selectedOptions = Array.from(document.querySelectorAll('input.variation-radio:checked'))
                                    .map(input => parseInt(input.value));

                                const matchedSet = variationSets.find(set =>
                                    arraysEqual(set.variation_option_ids, selectedOptions)
                                );

                                const priceEl = document.getElementById('product-price');
                                const stockEl = document.getElementById('stock-count');
                                const addToCartBtn = document.getElementById('add-to-cart-btn');

                                if (matchedSet) {
                                    priceEl.textContent = formatPrice(matchedSet.price);
                                    stockEl.textContent = matchedSet.stock;

                                    if (matchedSet.stock <= 0) {
                                        addToCartBtn.disabled = true;
                                        addToCartBtn.textContent = 'Out of Stock';
                                    } else {
                                        addToCartBtn.disabled = false;
                                        addToCartBtn.textContent = 'Add to Cart';
                                    }
                                } else {
                                    priceEl.textContent = formatPrice({{ $product->price }});
                                    stockEl.textContent = 'Select all options';
                                    addToCartBtn.disabled = true;
                                    addToCartBtn.textContent = 'Select Options';
                                }
                            }

                            // Add change event listeners to all variation radio buttons
                            document.querySelectorAll('input.variation-radio').forEach(radio => {
                                radio.addEventListener('change', updateProductInfo);
                            });

                            // Initialize with default values
                            updateProductInfo();

                            // Add to cart AJAX handling
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

                                    messageDiv.textContent = 'Product added to cart!';
                                    messageDiv.classList.remove('hidden');

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
