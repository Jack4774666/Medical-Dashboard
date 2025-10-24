<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class RevenueBusinessChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Sales Revenue (USD)';

    protected ?string $description = 'Displays total monthly revenue generated from sales.';
    protected ?int $chartHeight = 350;

    protected function getData(): array
    {
        // Get total revenue grouped by month
        $revenues = Sale::select(
                DB::raw('DATE_FORMAT(sale_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Extract the labels (months) and totals (values)
        $labels = $revenues->pluck('month')->map(function ($month) {
            return date('M Y', strtotime($month)); // e.g. "Jan 2025"
        })->toArray();

        $data = $revenues->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $data,
                    'borderColor' => 'rgba(37, 99, 235, 1)',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.3, // smooth curve
                    'pointBackgroundColor' => 'rgba(37, 99, 235, 1)',
                    'pointRadius' => 5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart for monthly trend
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Month',
                    ],
                    'ticks' => [
                        'color' => '#6b7280',
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)',
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => '#6b7280',
                        'stepSize' => 100,
                    ],
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'color' => '#374151',
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return "$" + context.parsed.y.toFixed(2);
                        }',
                    ],
                ],
            ],
        ];
    }
}
