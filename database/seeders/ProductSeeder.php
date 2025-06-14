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
        // Make sure at least one vendor exists
        $vendor = Vendor::first();

        if (!$vendor) {
            // Optionally create a default vendor here or abort
            $vendor = Vendor::create([
                'name' => 'Default Vendor',
                'slug' => 'default-vendor',
                'email' => 'vendor@example.com',
                'is_active' => true,
                // Add any other required fields (e.g., password)
            ]);

            $this->command->info('Default vendor created with ID ' . $vendor->id);
        }

        // Get a category to assign product to
        $category = Category::first();

        if (!$category) {
            $this->command->warn('No categories found. Run CategorySeeder first.');
            return;
        }

        // Use firstOrCreate to avoid duplicates on slug
        Product::firstOrCreate(
            ['slug' => 'kaftan-dress'],
            [
                'vendor_id' => $vendor->id,
                'category_id' => $category->id,
                'name' => 'Brand New Kaftan Dress',
                'description' => 'Flowy kaftan dress',
                'price' => 200,
                'stock' => 200,
                'is_active' => true,
            ]
        );
    }
}
