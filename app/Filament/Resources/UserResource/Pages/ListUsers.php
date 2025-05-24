<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    // Optional: Disable edit button if ever inherited
    protected function getHeaderActions(): array
    {
        return [];
    }
}
