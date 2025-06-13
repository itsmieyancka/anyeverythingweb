<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class StackedAreaSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Sales by Category (Stacked Area)';

    protected int|string|array $columnSpan = 3;  // allowed types
    protected string|int $height = '350px';      // allowed types
    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true, 'beginAtZero' => true],
            ],
            'elements' => [
                'line' => ['tension' => 0.4, 'fill' => true],
                'point' => ['radius' => 3],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
                'tooltip' => ['mode' => 'index', 'intersect' => false],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getData(): array
    {
        // Get all active categories
        $categories = Category::where('is_active', 1)->pluck('name')->toArray();

        // Prepare month labels
        $labels = [];
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = date('M', mktime(0, 0, 0, $month, 1));
        }

        // Initialize sales data array per category with zeros for each month
        $salesData = [];
        foreach ($categories as $category) {
            $salesData[$category] = array_fill(0, 12, 0);
        }

        // Query sales totals grouped by category and month
        // Assuming you have order_items table linking orders and products:
        // order_items: id, order_id, product_id, quantity, price
        // products: id, category_id, ...
        // orders: id, created_at, total, ...
        $sales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                'categories.name as category_name',
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
            )
            ->whereYear('orders.created_at', date('Y'))
            ->where('categories.is_active', 1)
            ->groupBy('month', 'category_name')
            ->orderBy('month')
            ->get();

        // Fill salesData with queried totals
        foreach ($sales as $record) {
            $monthIndex = $record->month - 1;
            $salesData[$record->category_name][$monthIndex] = (float) $record->total_sales;
        }

        // Define colors for categories (map or generate as needed)
        $backgroundColors = [
            'Electric Appliances' => 'rgba(54, 162, 235, 0.6)',
            'Mobile Phones' => 'rgba(255, 99, 132, 0.6)',
            'Fashion' => 'rgba(255, 206, 86, 0.6)',
            'Footwear' => 'rgba(75, 192, 192, 0.6)',
            'Home & Garden' => 'rgba(153, 102, 255, 0.6)',
            'Furniture' => 'rgba(255, 159, 64, 0.6)',
        ];

        $borderColors = [
            'Electric Appliances' => 'rgba(54, 162, 235, 1)',
            'Mobile Phones' => 'rgba(255, 99, 132, 1)',
            'Fashion' => 'rgba(255, 206, 86, 1)',
            'Footwear' => 'rgba(75, 192, 192, 1)',
            'Home & Garden' => 'rgba(153, 102, 255, 1)',
            'Furniture' => 'rgba(255, 159, 64, 1)',
        ];

        // Prepare datasets for Chart.js
        $datasets = [];
        foreach ($categories as $category) {
            $datasets[] = [
                'label' => $category,
                'data' => $salesData[$category],
                'backgroundColor' => $backgroundColors[$category] ?? 'rgba(100, 100, 100, 0.6)',
                'borderColor' => $borderColors[$category] ?? 'rgba(100, 100, 100, 1)',
                'borderWidth' => 2,
                'fill' => true,
                'tension' => 0.4,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}
