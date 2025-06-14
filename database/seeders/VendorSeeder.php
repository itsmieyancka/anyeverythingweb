<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'vendor@example.com')->first();

        if (!$user) {
            $this->command->warn('Vendor user not found. Skipping vendor creation.');
            return;
        }

        Vendor::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'Test Vendor',
                'description' => 'This is a test vendor',
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'commission_rate' => 10,
            ]
        );
    }
}
