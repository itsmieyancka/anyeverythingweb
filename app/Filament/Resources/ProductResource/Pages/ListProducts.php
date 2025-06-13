<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Actions\CreateAction;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    // This adds the "Create" button at the top of the list page
    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // These are actions available per record (row)
    protected function getTableActions(): array
    {
        return [
            Action::make('manageVariations')
                ->label('Variations')
                ->icon('heroicon-o-adjustments-vertical')
                ->url(fn ($record) => route('filament.admin.resources.products.manageVariations', ['record' => $record->id]))
                ->openUrlInNewTab(), // Optional: opens in new tab
        ];
    }
}


