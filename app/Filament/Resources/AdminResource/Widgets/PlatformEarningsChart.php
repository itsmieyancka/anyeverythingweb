<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PlatformEarningsChart extends ChartWidget
{
    protected static ?string $heading = 'Platform Earnings (Last 30 Days)';
    protected static ?int $sort = 1; // Optional: order of widget display

    protected int|string|array $columnSpan = 2;
    protected string|int $height = '300px';


    protected function getData(): array
    {
        $earnings = DB::table('orders')
            ->selectRaw('DATE(created_at) as date, SUM(platform_earnings) as total')
            ->whereNotNull('platform_earnings')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $earnings->pluck('date')->toArray();
        $data = $earnings->pluck('total')->map(fn($value) => (float)$value)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Earnings (ZAR)',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 10,
                        'maxTicksLimit' => 2,
                    ],
                ],
            ],
        ];
    }
}
