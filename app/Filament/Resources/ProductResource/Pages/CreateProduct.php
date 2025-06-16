<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Vendor;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): \App\Models\Product
    {
        // Get current logged-in user
        $user = Auth::user();

        // Get vendor linked to this user
        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            abort(404, 'Vendor record not found for current user.');
        }

        // Create product with vendor_id set correctly
        return \App\Models\Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? '',
            'price' => $data['price'] ?? 0,
            'stock' => $data['stock'] ?? 0,
            'color' => $data['color'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        // If no variation attributes or sets, treat as simple product and skip variation creation
        if (empty($data['variation_attributes']) || empty($data['variation_sets'])) {
            return;
        }

        // Map to store VariationOption IDs keyed by "AttributeName:OptionValue"
        $optionMap = [];

        // Save Variation Attributes and Options
        foreach ($data['variation_attributes'] as $attributeData) {
            $attribute = \App\Models\VariationType::firstOrCreate([
                'name' => $attributeData['name'],
            ]);
            foreach ($attributeData['options'] as $optionData) {
                $option = \App\Models\VariationOption::firstOrCreate([
                    'variation_type_id' => $attribute->id,
                    'value' => $optionData['value'],
                ]);
                $optionMap[$attribute->name . ':' . $option->value] = $option->id;
            }
        }

        // Save Variation Sets
        foreach ($data['variation_sets'] as $set) {
            $variationOptionIds = [];
            foreach ($set['variation_option_ids'] as $keyValue) {
                if (isset($optionMap[$keyValue])) {
                    $variationOptionIds[] = $optionMap[$keyValue];
                }
            }

            \App\Models\ProductVariationSet::create([
                'product_id' => $this->record->id,
                'price' => $set['price'],
                'stock' => $set['stock'],
                'is_active' => true,
            ])->variationOptions()->sync($variationOptionIds);
        }
    }
}
