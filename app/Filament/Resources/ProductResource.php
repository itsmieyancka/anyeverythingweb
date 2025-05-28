<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\VariationTypesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductVariationSetsRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

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
                ->minValue(0)
                ->helperText('Used if no variations are defined'),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->required()
                ->minValue(0)
                ->helperText('Used if no variations are defined'),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

            Forms\Components\Select::make('color')
                ->label('Color')
                ->options([
                    'green' => 'Green',
                    'red' => 'Red',
                    'blue' => 'Blue',
                    'black' => 'Black',
                    'white' => 'White',
                    // Add more colors or load dynamically as needed
                ])
                ->visible(fn (callable $get) => empty($get('variation_attributes')))
                ->helperText('Specify color for simple products without variations'),

            // Variation Attributes (optional)
            Forms\Components\Repeater::make('variation_attributes')
                ->label('Variation Attributes')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Attribute Name (e.g., Color, Size)')
                        ->required(),
                    Forms\Components\Repeater::make('options')
                        ->label('Options')
                        ->schema([
                            Forms\Components\TextInput::make('value')
                                ->label('Option Value (e.g., Red, Blue)')
                                ->required(),
                        ])
                        ->required()
                        ->minItems(1),
                ])
                ->columns(1)
                ->minItems(0)
                ->maxItems(5)
                ->helperText('Leave empty if product has no variations'),

            // Variation Sets (optional)
            Forms\Components\Repeater::make('variation_sets')
                ->label('Variation Combinations')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Price (ZAR)')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    Forms\Components\TextInput::make('stock')
                        ->label('Stock')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    Forms\Components\MultiSelect::make('variation_option_ids')
                        ->label('Variation Options')
                        ->options(function (callable $get) {
                            $attributes = $get('variation_attributes') ?? [];
                            $options = [];
                            foreach ($attributes as $attribute) {
                                foreach ($attribute['options'] as $option) {
                                    $key = $attribute['name'] . ':' . $option['value'];
                                    $options[$key] = $key; // Use composite keys
                                }
                            }
                            return $options;
                        })
                        ->required()
                        ->multiple(),
                ])
                ->columns(2)
                ->createItemButtonLabel('Add Variation Combination')
                ->minItems(0)
                ->helperText('Leave empty if product has no variations'),

            SpatieMediaLibraryFileUpload::make('images')
                ->collection('images')
                ->multiple()
                ->enableReordering()
                ->label('Product Images'),

            Forms\Components\Select::make('vendor_id')
                ->relationship('vendor', 'business_name')
                ->required()
                ->visible(fn () => auth()->user()->hasRole('admin'))
                ->default(fn () => auth()->id()),

            Forms\Components\Hidden::make('vendor_id')
                ->default(fn () => auth()->id())
                ->visible(fn () => !auth()->user()->hasRole('admin')),

            Forms\Components\Select::make('category_id')
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()->hasRole('admin')) {
            $query->where('vendor_id', auth()->id());
        }

        return $query;
    }
}


