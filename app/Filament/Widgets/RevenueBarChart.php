<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;

class RevenueBarChart extends ChartWidget
{
    protected ?string $heading = 'Students / Classes / Sections Overview';

    // Optional: make it full width
    protected ?int $chartHeight = 200; // ✅ slightly less

protected int|string|array $columnSpan = [
    'sm' => 2,
    'md' => 1,
];




    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => [400, 250, 300, 500, 700, 600],
                    'backgroundColor' => 'rgba(16,185,129,0.6)',
                ],
            ],
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
