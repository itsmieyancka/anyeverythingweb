<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VariationType;

class VariationSeeder extends Seeder
{
    public function run()
    {
        // Create Size variation type
        $sizeType = VariationType::firstOrCreate(['name' => 'Size']);
        $saSizes = ['SA 6', 'SA 8', 'SA 10', 'SA 12', 'SA 14', 'SA 16'];
        foreach ($saSizes as $size) {
            $sizeType->options()->firstOrCreate(['value' => $size]);
        }

        // Create Color variation type
        $colorType = VariationType::firstOrCreate(['name' => 'Color']);
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow'];
        foreach ($colors as $color) {
            $colorType->options()->firstOrCreate(['value' => $color]);
        }
    }
}
