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
     * Responds with JSON if AJAX, otherwise redirects (for non-AJAX fallback).
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variation_option_ids' => 'sometimes|array',
            'variation_option_ids.*' => 'integer|exists:variation_options,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $validated['quantity'] ?? 1;

        if (!empty($validated['variation_option_ids'])) {
            // Sort selected options to find matching variation set
            $selectedOptionIds = collect($validated['variation_option_ids'])->sort()->values()->toArray();

            $variationSet = ProductVariationSet::where('product_id', $product->id)
                ->get()
                ->first(function ($set) use ($selectedOptionIds) {
                    // variation_option_ids is stored as JSON or array
                    $optionIds = collect($set->variation_option_ids)->sort()->values()->toArray();
                    return $optionIds === $selectedOptionIds;
                });

            if (!$variationSet) {
                return $this->errorResponse($request, 'Selected product variation is not available.');
            }

            if ($variationSet->stock <= 0) {
                return $this->errorResponse($request, 'Selected variation is out of stock.');
            }

            $price = $variationSet->price;
            $variationSetId = $variationSet->id;
            $stock = $variationSet->stock;

            $cartItemKey = $product->id . '-' . implode('-', $selectedOptionIds);
        } else {
            if (($product->stock ?? 0) <= 0) {
                return $this->errorResponse($request, 'Product is out of stock.');
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
                'cartCount' => $this->getCartCount(),
            ]);
        }

        // fallback redirect (if form submits without JS)
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Display the cart contents.
     */
    public function index()
    {
        $cart = session('cart', []);

        // Eager load variationOptions for each variation set in cart
        foreach ($cart as &$item) {
            $item['variationSet'] = $item['variation_set_id']
                ? ProductVariationSet::with('variationOptions')->find($item['variation_set_id'])
                : null;
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

    /**
     * Helper to send error response depending on request type.
     */
    protected function errorResponse(Request $request, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json(['error' => $message], 422);
        }

        return redirect()->back()->withErrors($message);
    }

    /**
     * Helper to get total cart item count.
     */
    protected function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
}

