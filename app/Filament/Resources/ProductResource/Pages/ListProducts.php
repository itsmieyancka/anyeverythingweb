<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

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

