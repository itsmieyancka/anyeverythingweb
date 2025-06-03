<a href="{{ route('products.show', $product->id) }}" class="block rounded-lg border shadow hover:shadow-lg transition overflow-hidden">
    <div class="p-4">
        {{-- Product Image --}}
        <img
            src="{{ $product->getFirstMediaUrl('images') ?: asset('default-image.png') }}"
            alt="{{ $product->name }}"
            class="w-full h-48 object-cover rounded mb-4"
        />

        {{-- Product Name --}}
        <h3 class="text-lg font-semibold mb-1">{{ $product->name }}</h3>

        {{-- Product Price --}}
        <p class="text-gray-700 font-medium mb-4">R{{ number_format($product->price, 2) }}</p>

        {{-- Sold by --}}
        <p class="text-sm text-gray-500 mb-2">
            Sold by: <span class="font-medium">{{ $product->vendor->business_name ?? 'Unknown Vendor' }}</span>
        </p>

        {{-- Variations (excluding Size) --}}
        @if($product->variationTypes->count())
            @foreach ($product->variationTypes as $variationType)
                @if (strtolower($variationType->name) !== 'size')
                    <div class="mb-2">
                        <h4 class="text-sm font-semibold mb-1">{{ $variationType->name }}</h4>
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($variationType->variationOptions as $option)
                                <span
                                    class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                                    title="{{ $option->value }}"
                                    style="
                                        @if(strtolower($variationType->name) === 'color')
                                            background-color: {{ $option->value }};
                                            color: transparent;
                                        @else
                                            background-color: #f3f4f6;
                                            color: #111827;
                                        @endif
                                    "
                                >
                                    @if(strtolower($variationType->name) !== 'color')
                                        {{ $option->value }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @elseif($product->color)
            {{-- Fallback color display if no variationTypes exist --}}
            <div class="mb-2">
                <h4 class="text-sm font-semibold mb-1">Color</h4>
                <div class="flex gap-2 flex-wrap">
                    <span
                        class="w-6 h-6 rounded-full border-2 border-gray-300 cursor-default"
                        title="{{ ucfirst($product->color) }}"
                        style="background-color: {{ $product->color }};"
                    ></span>
                </div>
            </div>
        @endif

        {{-- Average Rating --}}
        @if ($product->ratings->count())
            <div class="flex items-center space-x-1 mt-2">
                <div class="rating rating-sm">
                    @for ($i = 1; $i <= 5; $i++)
                        <input type="radio" disabled class="mask mask-star-2 bg-green-500"
                            {{ round($product->averageRating()) == $i ? 'checked' : '' }} />
                    @endfor
                </div>
                <span class="text-sm text-gray-500">({{ $product->ratings->count() }})</span>
            </div>
        @endif
    </div>
</a>
