<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class VendorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make('Total Products', $user->products()->count()),
            Stat::make('Active Products', $user->products()->where('is_active', true)->count()),
            Stat::make('Total Orders', $user->orders()->count()),
            Stat::make('Pending Orders', $user->orders()->where('status', 'pending')->count()),
        ];
    }
}
