<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'media',
            'vendor',
            'variationTypes.variationOptions'
        ])->get();

        return view('products.index', compact('products'));
    }

    public function home()
    {
        $featuredProducts = Product::where('is_active', true)
            ->with(['media', 'vendor'])
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('featuredProducts'));
    }

    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)
            ->with([
                'products.media',
                'products.vendor',
            ])
            ->firstOrFail();

        return view('category.show', compact('category'));
    }

    public function showDepartments()
    {
        $departments = Department::where('is_active', true)
            ->with(['categories' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return view('departments.index', compact('departments'));
    }

    public function show(Product $product)
    {
        $product->load([
            'media',
            'vendor',
            'variationSets.variationOptions.variationType',
            'variationTypes.variationOptions',
            'ratings.user', // ✅ Load reviews and their authors
        ]);

        $variationGroups = [];
        foreach ($product->variationTypes as $variationType) {
            $variationGroups[$variationType->name] = $variationType->variationOptions->keyBy('id');
        }

        $variationSets = $product->variationSets->map(function ($set) {
            $optionIds = $set->variationOptions->pluck('id')->sort()->values()->toArray();

            return [
                'id' => $set->id,
                'variation_option_ids' => $optionIds,
                'price' => $set->price,
                'stock' => $set->stock,
            ];
        })->values()->toArray();

        // 👇 Optional: get user's own rating if logged in
        $userRating = null;
        if (auth()->check()) {
            $userRating = $product->ratings->where('user_id', auth()->id())->first();
        }

        return view('products.show', [
            'product' => $product,
            'variationGroups' => $variationGroups,
            'variationSets' => $variationSets,
            'userRating' => $userRating,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->with(['media', 'vendor'])
            ->get();

        return view('products.search', compact('products', 'query'));
    }
}
