<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $variationSet = $item['variation_set_id']
                ? ProductVariationSet::with('variationOptions')->find($item['variation_set_id'])
                : null;

            $items[] = [
                'product' => $product,
                'variationSet' => $variationSet,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }

        return view('checkout', ['cart' => $items]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'card_number' => 'required|string|min:16', // mock validation
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => strtoupper(Str::random(10)),
            'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'status' => 'processing',
        ]);

        foreach ($cart as $item) {
            // Reduce stock
            if ($item['variation_set_id']) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet && $variationSet->stock >= $item['quantity']) {
                    $variationSet->stock -= $item['quantity'];
                    $variationSet->save();
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            // Save item to order
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('order.confirmed', ['order' => $order->id]);
    }

    public function confirmed(Order $order)
    {
        return view('checkout.confirmed', compact('order'));
    }
}
