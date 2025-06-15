<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Add a product or product variation to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $user = auth()->user();

        // Prevent vendors from adding their own products to cart
        if ($user->hasRole('vendor') && $product->vendor->user_id === $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'You cannot add your own products to the cart.'], 403);
            }
            return redirect()->back()->withErrors('You cannot add your own products to the cart.');
        }

        $validated = $request->validate([
            'variation_option_ids' => 'sometimes|array',
            'variation_option_ids.*' => 'integer|exists:variation_options,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $validated['quantity'] ?? 1;

        if (!empty($validated['variation_option_ids'])) {
            $selectedOptionIds = collect($validated['variation_option_ids'])->sort()->values()->toArray();

            $variationSet = ProductVariationSet::where('product_id', $product->id)
                ->get()
                ->first(function ($set) use ($selectedOptionIds) {
                    $optionIds = collect($set->variation_option_ids)->sort()->values()->toArray();
                    return $optionIds === $selectedOptionIds;
                });

            if (!$variationSet) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Selected product variation is not available.'], 422);
                }
                return redirect()->back()->withErrors('Selected product variation is not available.');
            }

            if ($variationSet->stock <= 0) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Selected variation is out of stock.'], 422);
                }
                return redirect()->back()->withErrors('Selected variation is out of stock.');
            }

            $price = $variationSet->price;
            $variationSetId = $variationSet->id;
            $stock = $variationSet->stock;

            $cartItemKey = $product->id . '-' . implode('-', $selectedOptionIds);
        } else {
            if (($product->stock ?? 0) <= 0) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Product is out of stock.'], 422);
                }
                return redirect()->back()->withErrors('Product is out of stock.');
            }

            $price = $product->price;
            $variationSetId = null;
            $stock = $product->stock;

            $cartItemKey = $product->id;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$cartItemKey])) {
            $newQuantity = $cart[$cartItemKey]['quantity'] + $quantity;
            $cart[$cartItemKey]['quantity'] = min($newQuantity, $stock);
        } else {
            $cart[$cartItemKey] = [
                'product_id' => $product->id,
                'variation_set_id' => $variationSetId,
                'quantity' => min($quantity, $stock),
                'price' => $price,
                'vendor_id' => $product->vendor_id,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart!',
                'cartCount' => count($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // ... other methods (index, remove) unchanged ...
}
