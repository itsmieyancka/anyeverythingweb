<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Add a product variation to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variation_option_ids' => 'required|array',
            'variation_option_ids.*' => 'integer|exists:variation_options,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $selectedOptionIds = collect($validated['variation_option_ids'])->sort()->values()->toArray();

        // Find matching variation set for the selected options
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

        $quantity = $validated['quantity'] ?? 1;

        // Retrieve existing cart from session or initialize empty array
        $cart = session()->get('cart', []);

        // Create a unique key for the cart item based on product and variation options
        $cartItemKey = $product->id . '-' . implode('-', $selectedOptionIds);

        if (isset($cart[$cartItemKey])) {
            // Increase quantity if item already in cart
            $cart[$cartItemKey]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $cart[$cartItemKey] = [
                'product_id' => $product->id,
                'variation_set_id' => $variationSet->id,
                'quantity' => $quantity,
                'price' => $variationSet->price,
            ];
        }

        // Save updated cart back to session
        session()->put('cart', $cart);

        // Respond with JSON if AJAX request
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart!',
                'cartCount' => count($cart),
            ]);
        }

        // Normal form submission fallback
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Display the cart contents.
     */
    public function index()
    {
        $cart = session('cart', []);

        // Eager load variationOptions for each variation set in cart
        foreach ($cart as &$item) {
            $item['variationSet'] = ProductVariationSet::with('variationOptions')->find($item['variation_set_id']);
            $item['product'] = Product::find($item['product_id']);
        }

        return view('cart.index', compact('cart'));
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($key): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}

