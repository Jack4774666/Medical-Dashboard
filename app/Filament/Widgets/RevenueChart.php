<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Overview';
    protected ?string $description = 'Monthly revenue trend';
    protected string $color = 'primary';
    protected ?string $maxHeight = '350px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Group by month and sum total_amount
        $revenues = Sale::select(
            DB::raw('DATE_FORMAT(sale_date, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $revenues->pluck('month')->map(fn($m) => date('M Y', strtotime($m . '-01')));
        $data = $revenues->pluck('total');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $data,
                    'fill' => true,
                    'borderColor' => '#3b82f6', // soft blue line
                    'backgroundColor' => 'rgba(59,130,246,0.15)', // gradient fill
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor' => '#fff',
                    'pointHoverRadius' => 6,
                    'pointRadius' => 4,
                    'tension' => 0.4, // smooth line
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
            'plugins' => [
                'legend' => [
                    'display' => false, // hide legend for a clean look
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false, // no vertical grid lines
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => 'rgba(0,0,0,0.05)',
                    ],
                    'ticks' => [
                        'beginAtZero' => true,
                        'callback' => "function(value){return '$' + value;}",
                    ],
                ],
            ],
        ];
    }
}
