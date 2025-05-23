<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\VariationTypesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductVariationSetsRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';  // Use a valid heroicon name

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->maxLength(65535),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required()
                ->minValue(0),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->required()
                ->minValue(0),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

            SpatieMediaLibraryFileUpload::make('images')
                ->collection('images')
                ->multiple()
                ->enableReordering()
                ->label('Product Images'),

            Select::make('vendor_id')
                ->relationship('vendor', 'name')
                ->required()
                ->visible(fn () => auth()->user()->hasRole('admin')), // only admins see this field

            Select::make('category_id')
                ->label('Category')
                ->options(\App\Models\Category::pluck('name', 'id'))
                ->required(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('price')->money('ZAR', true),
            Tables\Columns\TextColumn::make('stock')->sortable(),
            Tables\Columns\BooleanColumn::make('is_active'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VariationTypesRelationManager::class,
            ProductVariationSetsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    /**
     * Customize the base Eloquent query to restrict products by vendor for non-admin users.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()->hasRole('admin')) {
            $query->where('vendor_id', auth()->id());
        }

        return $query;
    }
}

