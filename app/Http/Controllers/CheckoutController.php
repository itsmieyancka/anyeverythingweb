<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'card_number' => 'required|string',
            'expiry' => 'required|string',
            'cvc' => 'required|string',
            'shipping_method' => 'required|in:standard,express',
        ]);

        // Calculate subtotal
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Shipping costs
        $shippingCosts = [
            'standard' => 40,
            'express' => 80,
        ];
        $shippingCost = $shippingCosts[$request->input('shipping_method')];

        // Commission: 10 R per sold item
        $totalQuantity = collect($cart)->sum('quantity');
        $commission = $totalQuantity * 10;

        // Calculate total
        $total = $subtotal + $shippingCost;

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'processing',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'commission' => $commission,
            'total' => $total,
            'shipping_address' => $request->input('address'),
            'shipping_method' => $request->input('shipping_method'),
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Reduce stock
            if ($item['variation_set_id']) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet) {
                    $variationSet->decrement('stock', $item['quantity']);
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }
        }

        session()->forget('cart');

        return redirect()->route('order.confirmed', $order);
    }

    public function confirmed(Order $order)
    {
        return view('checkout.confirmed', compact('order'));
    }
}

