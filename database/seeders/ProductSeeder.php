<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::updateOrCreate([
            'slug' => 'kaftan-dress',
        ], [
            'vendor_id' => 1, // Adjust as needed
            'category_id' => 3, // Adjust as needed
            'name' => 'Brand New Kaftan Dress',
            'description' => 'Flowy kaftan dress',
            'price' => 200.00,
            'stock' => 200,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add more products if needed
    }
}

