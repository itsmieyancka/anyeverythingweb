<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Example departments (ensure these exist or change the logic to use real ones)
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Run DepartmentSeeder first.');
            return;
        }

        foreach ($departments as $department) {
            // Create parent categories
            $parent = Category::create([
                'department_id' => $department->id,
                'name' => 'Electronics - ' . $department->name,
                'slug' => Str::slug('Electronics - ' . $department->name),
                'description' => 'All kinds of electronics in ' . $department->name,
                'is_active' => true,
                'parent_id' => null,
            ]);

            // Create child categories under parent
            Category::create([
                'department_id' => $department->id,
                'name' => 'Mobile Phones - ' . $department->name,
                'slug' => Str::slug('Mobile Phones - ' . $department->name),
                'description' => 'Smartphones under electronics in ' . $department->name,
                'is_active' => true,
                'parent_id' => $parent->id,
            ]);
        }
    }
}
