<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Electronics',
                'description' => 'Devices, gadgets, and accessories.',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing, shoes, and accessories.',
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Furniture, tools, and outdoor goods.',
            ],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(
                ['name' => $data['name']],
                [
                    'slug' => Str::slug($data['name']),
                    'description' => $data['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
