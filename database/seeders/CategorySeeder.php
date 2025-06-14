<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Loop through all departments
        $departments = Department::all();

        foreach ($departments as $department) {

            // Example categories per department (customize as needed)
            $categories = [
                [
                    'name' => 'Electronics - ' . $department->name,
                    'description' => 'All kinds of electronics in ' . $department->name,
                    'parent_id' => null,
                ],
                [
                    'name' => 'Accessories - ' . $department->name,
                    'description' => 'Accessories related to ' . $department->name,
                    'parent_id' => null,
                ],
                // Add more categories here if needed
            ];

            foreach ($categories as $cat) {
                $slug = Str::slug($cat['name']);

                Category::firstOrCreate(
                    ['slug' => $slug], // search by slug to avoid duplicates
                    [
                        'department_id' => $department->id,
                        'name' => $cat['name'],
                        'description' => $cat['description'],
                        'is_active' => true,
                        'parent_id' => $cat['parent_id'],
                    ]
                );
            }
        }
    }
}

