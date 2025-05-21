<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VariationType;
use App\Models\VariationOption;

class VariationOptionsSeeder extends Seeder
{
    public function run()
    {
        $size = VariationType::where('name', 'Size')->first();
        $color = VariationType::where('name', 'Color')->first();

        if ($size) {
            $sizeOptions = ['Small', 'Medium', 'Large'];
            foreach ($sizeOptions as $option) {
                VariationOption::firstOrCreate([
                    'variation_type_id' => $size->id,
                    'value' => $option,
                ]);
            }
        }

        if ($color) {
            $colorOptions = ['Red', 'Blue', 'Green'];
            foreach ($colorOptions as $option) {
                VariationOption::firstOrCreate([
                    'variation_type_id' => $color->id,
                    'value' => $option,
                ]);
            }
        }
    }
}
