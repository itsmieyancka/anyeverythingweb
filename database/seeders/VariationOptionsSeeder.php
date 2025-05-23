<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\VariationType;
use App\Models\VariationOption;

class VariationOptionsSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $sizeType = VariationType::firstOrCreate(
                ['product_id' => $product->id, 'name' => 'Size'],
                ['created_at' => now(), 'updated_at' => now()]
            );

            $colorType = VariationType::firstOrCreate(
                ['product_id' => $product->id, 'name' => 'Color'],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Sizes to add
            $sizes = ['Small', 'Large'];
            foreach ($sizes as $size) {
                VariationOption::updateOrCreate(
                    ['variation_type_id' => $sizeType->id, 'value' => $size],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }

            // Colors to add
            $colors = ['Red', 'Blue'];
            foreach ($colors as $color) {
                VariationOption::updateOrCreate(
                    ['variation_type_id' => $colorType->id, 'value' => $color],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
