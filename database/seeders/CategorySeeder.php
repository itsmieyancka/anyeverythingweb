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
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Skipping category seeding.');
            return;
        }

        foreach ($departments as $department) {
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
            ];

            foreach ($categories as $cat) {
                $slug = Str::slug($cat['name']);

                Category::firstOrCreate(
                    ['slug' => $slug],
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

        $this->command->info('Categories seeded successfully.');
    }
}


