<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;

class VariationOptionsRelationManager extends RelationManager
{
protected static string $relationship = 'variationOptions';

public function form(Form $form): Form
{
return $form->schema([
TextInput::make('value')->required(),
SpatieMediaLibraryFileUpload::make('image')
->collection('variation-option-images')
->label('Option Image')
->image()
->maxSize(1024),
]);
}

public function table(Table $table): Table
{
return $table
->columns([
ImageColumn::make('image')->collection('variation-option-images'),
TextColumn::make('value'),
]);
}
}
