<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class InventoryBarChart extends ChartWidget
{
    protected ?string $heading = 'Inventory Overview';
    protected ?string $description = 'Current stock levels of items in storage';
    protected string $color = 'info';
    protected ?string $maxHeight = '400px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $items = Item::select('name', 'stock_quantity')->orderBy('name')->get();

        if ($items->isEmpty()) {
            $labels = ['No Items'];
            $data = [0];
        } else {
            $labels = $items->pluck('name')->toArray();
            $data = $items->pluck('stock_quantity')->toArray();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quantity in Stock',
                    'data' => $data,
                    'backgroundColor' => 'rgba(37,99,235,0.6)',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => 'rgba(37,99,235,0.8)',
                    'hoverBorderColor' => '#1e3a8a',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                // ✅ Enable datalabels plugin
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'top',
                    'color' => '#1f2937', // dark gray text
                    'font' => [
                        'weight' => 'bold',
                        'size' => 12,
                    ],
                    'formatter' => 'function(value) {
                        return value + " units";
                    }',
                ],

                'legend' => [
                    'display' => true,
                    'labels' => [
                        'color' => '#374151',
                        'font' => ['size' => 12],
                    ],
                ],

                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed.y + " units";
                        }',
                    ],
                ],
            ],

            // ✅ Configure axes
            'scales' => [
                'x' => [
                    'ticks' => [
                        'color' => '#6b7280',
                        'autoSkip' => false,
                        'maxRotation' => 45,
                        'minRotation' => 0,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => '#6b7280',
                        'stepSize' => 5,
                        'callback' => 'function(value) { return value + " units"; }',
                    ],
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
            ],
        ];
    }
}
