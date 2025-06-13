<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\VariationType;

class VariationTypesSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            VariationType::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'Size'],
                ['created_at' => now(), 'updated_at' => now()]
            );

            VariationType::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'Color'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
