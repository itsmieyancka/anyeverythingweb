<?php

namespace Database\Seeders;

// database/seeders/VendorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Create a user that owns the vendor
        $user = User::firstOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Vendor User',
                'password' => bcrypt('password'), // use Hash::make if preferred
                'role' => 'vendor',
            ]
        );

        // Step 2: Create the vendor linked to that user
        Vendor::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'Test Vendor',
                'description' => 'This is a test vendor',
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'commission_rate' => 10, // optional: match your DB column
            ]
        );
    }
}
