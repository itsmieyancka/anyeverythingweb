<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\MultiSelect;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;

class VariationSetsRelationManager extends RelationManager
{
    protected static string $relationship = 'variationSets';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Product Variations';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),

            MultiSelect::make('variationOptions')
                ->relationship('variationOptions', 'value')
                ->preload()
                ->label('Options (e.g. Size, Color)')
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('price')->money('ZAR'),
            TextColumn::make('stock'),
            BooleanColumn::make('is_active')->label('Active'),
            TextColumn::make('variationOptions')
                ->label('Options')
                ->formatStateUsing(fn ($options) => collect($options)->pluck('value')->join(', ')),
        ]);
    }
}
