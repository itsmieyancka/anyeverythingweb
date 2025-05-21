<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\VariationType;

class VariationTypesSeeder extends Seeder
{
    public function run()
    {
        // Get all existing products
        $products = Product::all();

        foreach ($products as $product) {
            // Add sample variation types for each product, if none exist yet
            if ($product->variationTypes()->count() === 0) {
                VariationType::create([
                    'product_id' => $product->id,
                    'name' => 'Size',
                ]);

                VariationType::create([
                    'product_id' => $product->id,
                    'name' => 'Color',
                ]);
            }
        }
    }
}

