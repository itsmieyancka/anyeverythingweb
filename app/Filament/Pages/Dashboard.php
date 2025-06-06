<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function widgets(): array
    {
        return [
            \App\Filament\Widgets\StackedAreaSalesChart::class,
            \App\Filament\Resources\AdminResource\Widgets\PlatformEarningsChart::class,
        ];
    }
}



