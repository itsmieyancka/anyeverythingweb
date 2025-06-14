<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\User; // <--- Add this

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Find or create the user
        $user = User::firstOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Test Vendor User',
                'password' => bcrypt('password'),
                'role' => 'vendor',
            ]
        );

        // Step 2: Find or create the vendor linked to that user
        $vendor = Vendor::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'Test Vendor',
                'description' => 'This is a test vendor',
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'commission_rate' => 10,
            ]
        );

        // Step 3: Find the category "Fashion"
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
