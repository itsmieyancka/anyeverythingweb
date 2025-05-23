<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create roles if they don't exist already
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 2. Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('adminpassword'),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Create vendor user
        $vendor = User::firstOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Vendor User',
                'password' => Hash::make('vendorpassword'),
            ]
        );
        $vendor->assignRole($vendorRole);

        // 4. Create regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('userpassword'),
            ]
        );
        $user->assignRole($userRole);


        // 5. Call other seeders
        $this->call([
            DepartmentSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            VariationTypesSeeder::class,
            VariationOptionsSeeder::class,
            ProductVariationSetsSeeder::class,
        ]);
    }
}

