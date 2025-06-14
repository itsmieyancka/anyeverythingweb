<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first vendor or create one if none exists
        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'name' => 'Default Vendor',
                'slug' => 'default-vendor',
                'email' => 'vendor@example.com',
                'is_active' => true,
                // Add any other required fields here, e.g., password
            ]);
        }

        // Get a category to assign the product to - pick first active category
        $category = Category::where('is_active', true)->first();
        if (!$category) {
            // If no category exists, exit or create one here
            $this->command->error('No categories found, please seed categories first!');
            return;
        }

        // Example products - customize as needed
        $products = [
            [
                'name' => 'Brand New Kaftan Dress',
                'description' => 'Flowy kaftan dress',
                'price' => 200,
                'stock' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Noise cancelling headphones',
                'price' => 1500,
                'stock' => 50,
                'is_active' => true,
            ],
            // Add more products here
        ];

        foreach ($products as $prod) {
            $slug = Str::slug($prod['name']);

            Product::firstOrCreate(
                ['slug' => $slug], // prevent duplicates by slug
                [
                    'vendor_id' => $vendor->id,
                    'category_id' => $category->id,
                    'name' => $prod['name'],
                    'description' => $prod['description'],
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'is_active' => $prod['is_active'],
                ]
            );
        }
    }
}

