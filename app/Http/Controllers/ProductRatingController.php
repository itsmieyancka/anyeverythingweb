<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!auth()->user()->hasPurchased($product)) {
            return back()->withErrors(['error' => 'You can only rate products you have purchased.']);
        }

        ProductRating::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        return back()->with('success', 'Thank you for your rating!');
    }

}
