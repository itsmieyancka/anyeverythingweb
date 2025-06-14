<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure there's at least one vendor
        $vendor = Vendor::firstOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Test Vendor',
                'password' => bcrypt('password'), // required if using authentication
            ]
        );

        // Ensure category "Fashion" exists
        $category = Category::where('slug', 'fashion')->first();

        if ($vendor && $category) {
            Product::updateOrCreate(
                ['slug' => 'kaftan-dress'],
                [
                    'vendor_id' => $vendor->id,
                    'category_id' => $category->id,
                    'name' => 'Brand New Kaftan Dress',
                    'description' => 'Flowy kaftan dress',
                    'price' => 200.00,
                    'stock' => 200,
                    'is_active' => 1,
                ]
            );
        } else {
            $this->command->error('Vendor or category not found — Product not seeded.');
        }
    }
}
