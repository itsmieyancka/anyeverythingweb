<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Admin Report';
    protected static string $view = 'filament.admin.pages.admin-report';

    public array $stats = [];

    public function mount(): void
    {
        $this->stats = [
            'total_users' => User::count(),
            'total_vendors' => User::role('vendor')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}

