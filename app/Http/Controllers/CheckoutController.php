<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Show the checkout page
        return view('checkout');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:1000',
            'phone' => 'nullable|string|max:20',
        ]);

        // Here you would handle order saving, payment, etc.
        // For now, just clear the cart session and redirect:

        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
