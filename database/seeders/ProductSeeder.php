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
        $vendor = Vendor::first();
        $category = Category::first();

        if (!$vendor) {
            $this->command->warn('No vendor found. Skipping product seeding.');
            return;
        }

        if (!$category) {
            $this->command->warn('No category found. Skipping product seeding.');
            return;
        }

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

        $this->command->info('Product seeded successfully.');
    }
}
