<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\VariationOption;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Product;
use Filament\Notifications\Notification;

class ManageProductVariations extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = \App\Filament\Resources\ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.manage-product-variations';

    public Product $record;

    public array $variation_sets = [];

    public function mount(Product $record): void
    {
        $this->record = $record;
        $this->form->fill([
            'variation_sets' => $record->variationSets->map(function ($set) {
                return [
                    'variation_option_ids' => $set->variation_option_ids,
                    'price' => $set->price,
                    'stock' => $set->stock,
                ];
            })->toArray(),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('variation_sets')
                ->label('Variation Sets')
                ->schema([
                    Select::make('variation_option_ids')
                        ->label('Options')
                        ->multiple()
                        ->options(VariationOption::pluck('value', 'id'))
                        ->required(),

                    TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->required(),

                    TextInput::make('stock')
                        ->label('Stock')
                        ->numeric()
                        ->required(),
                ])
        ];
    }

    public function save()
    {
        $this->record->variationSets()->delete(); // Clear old sets

        foreach ($this->variation_sets as $set) {
            $this->record->variationSets()->create([
                'variation_option_ids' => $set['variation_option_ids'],
                'price' => $set['price'],
                'stock' => $set['stock'],
            ]);
        }

        Notification::make()
            ->title('Variations updated successfully!')
            ->success()
            ->send();
    }
}
