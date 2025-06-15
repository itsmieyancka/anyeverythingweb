<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categoriesPerDepartment = [
            'Electronics' => [
                ['name' => 'Mobile Phones', 'description' => 'Smartphones and mobile devices'],
                ['name' => 'Laptops', 'description' => 'Notebooks and portable computers'],
                ['name' => 'Accessories', 'description' => 'Chargers, cables, and more'],
            ],
            'Fashion' => [
                ['name' => 'Men\'s Clothing', 'description' => 'Shirts, jeans, and more'],
                ['name' => 'Women\'s Clothing', 'description' => 'Dresses, skirts, and tops'],
                ['name' => 'Accessories', 'description' => 'Bags, belts, and jewelry'],
            ],
            'Home & Garden' => [
                ['name' => 'Furniture', 'description' => 'Chairs, tables, and beds'],
                ['name' => 'Kitchenware', 'description' => 'Utensils and appliances'],
                ['name' => 'Garden Tools', 'description' => 'Shovels, rakes, and hoses'],
            ],
        ];

        foreach ($categoriesPerDepartment as $departmentName => $categories) {
            $department = Department::where('name', $departmentName)->first();

            if (!$department) {
                $this->command->warn("Department '$departmentName' not found. Skipping.");
                continue;
            }

            foreach ($categories as $cat) {
                Category::firstOrCreate(
                    [
                        'department_id' => $department->id,
                        'name' => $cat['name'],
                    ],
                    [
                        'slug' => \Str::slug($cat['name']),
                        'description' => $cat['description'],
                        'parent_id' => null,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info(' Categories seeded cleanly and logically.');
    }
}


