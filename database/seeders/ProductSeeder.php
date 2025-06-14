<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = Vendor::first();
        $category = Category::first();

        if (!$vendor || !$category) {
            $this->command->error('Missing vendor or category. Please run VendorSeeder and CategorySeeder first.');
            return;
        }

        Product::firstOrCreate([
            'slug' => 'kaftan-dress',
        ], [
            'name' => 'Brand New Kaftan Dress',
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'description' => 'Flowy kaftan dress',
            'price' => 200,
            'stock' => 200,
            'is_active' => true,
        ]);
    }
}
