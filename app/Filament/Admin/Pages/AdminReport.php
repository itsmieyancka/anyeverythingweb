<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminReport extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Admin Report';
    protected static string $view             = 'filament.admin.pages.admin-report';

    public array $stats = [];

    public function mount(): void
    {
        $this->stats = [
            // → Dynamic counts
            'total_users'     => User::count(),
            'total_vendors'   => User::role('vendor')->count(),
            'total_products'  => Product::count(),
            'total_orders'    => Order::count(),

            // → Static values (hardcoded for demonstration)
            'total_likes'     => 25600,     // e.g. “25.6K”
            'total_pageviews' => 2600000,   // e.g. “2.6M”
            'tasks_done'      => 86,        // e.g. “86%”
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
