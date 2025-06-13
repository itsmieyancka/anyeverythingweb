<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;

class ManageVariationOptions extends Page
{
protected static ?string $navigationIcon = 'heroicon-o-adjustments';
protected static string $view = 'filament.pages.manage-variation-options';

protected static bool $shouldRegisterNavigation = false;

public Product $product;

public static function getSlug(): string
{
return 'products/{record}/variation-options';
}

    public static function getRouteName(?string $panel = null): string
    {
        return 'filament.admin.pages.manage-variation-options';
    }


public function mount(Product $record)
{
$this->product = $record;
}
}
