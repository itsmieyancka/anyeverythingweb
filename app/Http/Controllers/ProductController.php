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
            ->take(4)
            ->get();

        $featuredIds = $featuredProducts->pluck('id');

        $bestRatedProducts = Product::where('is_active', true)
            ->whereNotIn('id', $featuredIds)
            ->whereHas('ratings') // ✅ Only include rated products
            ->with(['media', 'vendor', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(4)
            ->get();

        $departments = Department::where('is_active', true)->get();
        $categories = Category::where('is_active', 1)->get();


        return view('home', compact('featuredProducts', 'bestRatedProducts', 'departments', 'categories'));


    }

    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)
            ->with([
                'products.media',
                'products.vendor',
            ])
            ->firstOrFail();

        return view('categories.show', compact('category'));
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

        $departments = Department::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('products.search', compact('products', 'query'));

    }

    public function showCategories(Request $request)
    {
        $filterSlug = $request->query('filter');

        if ($filterSlug) {
            $category = Category::where('slug', $filterSlug)
                ->with(['products.media', 'products.vendor'])
                ->firstOrFail();

            return view('categories.index', [
                'categories' => $category,
            ]);
        }

        $categories = Category::where('is_active', true)->get();

        return view('categories.index', [
            'categories' => null,
            'categories' => $categories,
        ]);
    }

    // Show all categories list
    public function indexCategories()
    {
        $categories = Category::where('is_active', true)->get();

        return view('categories.index', compact('categories'));
    }


}
