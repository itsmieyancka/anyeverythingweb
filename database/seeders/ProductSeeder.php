<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create a vendor first if it doesn't exist
        $vendor = Vendor::firstOrCreate(
            ['email' => 'vendor@example.com'],
            ['name' => 'Test Vendor', 'password' => bcrypt('password')]
        );

        Product::updateOrCreate([
            'slug' => 'kaftan-dress',
        ], [
            'vendor_id' => $vendor->id,
            'category_id' => 3, // Make sure this category also exists
            'name' => 'Brand New Kaftan Dress',
            'description' => 'Flowy kaftan dress',
            'price' => 200.00,
            'stock' => 200,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
