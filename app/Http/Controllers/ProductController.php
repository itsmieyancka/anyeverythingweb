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
        $products = Product::with(['media', 'variationTypes.variationOptions'])->get();
        return view('products.index', compact('products'));
    }

    public function home()
    {
        $featuredProducts = Product::where('is_active', true)
            ->with('media')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('featuredProducts'));
    }

    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['products.media'])
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
        // Eager load relationships for performance
        $product->load(['media', 'variationSets.variationOptions.variationType', 'variationTypes.variationOptions']);

        // Group variation options by variation type (e.g., Color => [Red, Blue])
        $variationGroups = [];

        foreach ($product->variationTypes as $variationType) {
            $variationGroups[$variationType->name] = $variationType->variationOptions->keyBy('id');
        }

        // Prepare variationSets data for JavaScript (array of variation_option_ids, price, stock)
        $variationSets = $product->variationSets->map(function ($set) {
            $optionIds = collect($set->variation_option_ids);
            $optionIds = collect($optionIds)
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->toArray();

            return [
                'id' => $set->id,
                'variation_option_ids' => $optionIds,
                'price' => $set->price,
                'stock' => $set->stock,
            ];
        })->values()->toArray();

        return view('products.show', [
            'product' => $product,
            'variationGroups' => $variationGroups,
            'variationSets' => $variationSets,
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
}
