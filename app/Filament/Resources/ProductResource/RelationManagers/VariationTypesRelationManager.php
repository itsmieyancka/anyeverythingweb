<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\VariationType;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\VariationOptionsRelationManager;
class VariationTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'variationTypes';
    protected static ?string $title = 'Variation Types';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Name'),
        ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VariationOptionsRelationManager::class,
        ];
    }
}
