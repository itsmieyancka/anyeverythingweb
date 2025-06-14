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
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Run DepartmentSeeder first.');
            return;
        }

        foreach ($departments as $department) {
            // Create or get parent category
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug('Electronics - ' . $department->name)],
                [
                    'department_id' => $department->id,
                    'name' => 'Electronics - ' . $department->name,
                    'description' => 'All kinds of electronics in ' . $department->name,
                    'is_active' => true,
                    'parent_id' => null,
                ]
            );

            // Create or get child category
            Category::firstOrCreate(
                ['slug' => Str::slug('Mobile Phones - ' . $department->name)],
                [
                    'department_id' => $department->id,
                    'name' => 'Mobile Phones - ' . $department->name,
                    'description' => 'Smartphones under electronics in ' . $department->name,
                    'is_active' => true,
                    'parent_id' => $parent->id,
                ]
            );
        }

        // Explicitly create Fashion category for the Fashion department
        $fashionDepartment = Department::where('slug', 'fashion')->first();

        if ($fashionDepartment) {
            Category::firstOrCreate(
                ['slug' => 'fashion'],
                [
                    'department_id' => $fashionDepartment->id,
                    'name' => 'Fashion',
                    'description' => 'Clothing, shoes, and accessories.',
                    'is_active' => true,
                    'parent_id' => null,
                ]
            );
        } else {
            $this->command->warn('Fashion department not found. Cannot create Fashion category.');
        }
    }
}
