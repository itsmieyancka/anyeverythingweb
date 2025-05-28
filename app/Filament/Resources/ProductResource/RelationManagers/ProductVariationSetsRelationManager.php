<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\VariationOption;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class ProductVariationSetsRelationManager extends RelationManager
{
    protected static string $relationship = 'variationSets';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('price')
                ->label('Price (ZAR)')
                ->required()
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('stock')
                ->required()
                ->numeric()
                ->minValue(0),

            Forms\Components\MultiSelect::make('variation_option_ids')
                ->label('Variation Options')
                ->options(VariationOption::all()->pluck('value', 'id'))
                ->required()
                ->multiple(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('price')->money('ZAR', true),
            Tables\Columns\TextColumn::make('stock'),
            Tables\Columns\TagsColumn::make('variationOptions.value')
                ->label('Variation Options'),
        ]);
    }

    // Hook to sync the many-to-many relationship after save
    protected function afterSave(): void
    {
        $variationOptionIds = $this->form->getState()['variation_option_ids'] ?? [];

        $this->record->variationOptions()->sync($variationOptionIds);
    }
}
