<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariationSet;
use App\Models\VariationOption;

class ProductVariationSetsSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Get variation options for this product
            $sizeType = $product->variationTypes()->where('name', 'Size')->first();
            $colorType = $product->variationTypes()->where('name', 'Color')->first();

            if (!$sizeType || !$colorType) {
                $this->command->warn("Skipping product ID {$product->id} because variation types missing.");
                continue;
            }

            $sizes = VariationOption::where('variation_type_id', $sizeType->id)->get();
            $colors = VariationOption::where('variation_type_id', $colorType->id)->get();

            // Create combinations of Size + Color
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $variationOptionIds = [$size->id, $color->id];
                    sort($variationOptionIds);

                    ProductVariationSet::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'variation_option_ids' => json_encode($variationOptionIds),
                        ],
                        [
                            'price' => $product->price,
                            'stock' => 100, // Default stock, adjust as needed
                            'is_active' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
