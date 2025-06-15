<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Electronics
        Category::updateOrCreate([
            'slug' => 'mobile-phones',
        ], [
            'department_id' => 1,
            'name' => 'Mobile Phones',
            'description' => 'Smartphones, iPhones, and Androids',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::updateOrCreate([
            'slug' => 'laptops',
        ], [
            'department_id' => 1,
            'name' => 'Laptops',
            'description' => 'All kinds of laptops',
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Fashion
        Category::updateOrCreate([
            'slug' => 'mens-clothing',
        ], [
            'department_id' => 2,
            'name' => "Men's Clothing",
            'description' => 'Clothing for men',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::updateOrCreate([
            'slug' => 'womens-clothing',
        ], [
            'department_id' => 2,
            'name' => "Women's Clothing",
            'description' => 'Clothing for women',
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Home & Garden
        Category::updateOrCreate([
            'slug' => 'furniture',
        ], [
            'department_id' => 3,
            'name' => 'Furniture',
            'description' => 'Chairs, tables, and more',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::updateOrCreate([
            'slug' => 'garden-tools',
        ], [
            'department_id' => 3,
            'name' => 'Garden Tools',
            'description' => 'Tools for outdoor and garden',
            'is_active' => true,
            'parent_id' => null,
        ]);

        $this->command->info('Clean and correct categories seeded.');
    }
}
