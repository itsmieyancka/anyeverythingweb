<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Pages
use App\Filament\Admin\Pages\AdminReport;

// Resources
use App\Filament\Resources\UserResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\VendorResource;
use App\Filament\Resources\ProductResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                UserResource::class,
                CategoryResource::class,
                DepartmentResource::class,
                VendorResource::class,
                ProductResource::class,
            ])
            ->pages([
                \App\Filament\Pages\Dashboard::class, // Your custom dashboard page
                AdminReport::class,


            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                \App\Filament\Widgets\StackedAreaSalesChart::class, // Add your stacked area chart widget here if you want it globally
                 \App\Filament\Resources\AdminResource\Widgets\PlatformEarningsChart::class,
                \App\Filament\Widgets\TeamChatWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
