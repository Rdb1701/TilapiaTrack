<?php

namespace App\Filament\Widgets;

use App\Models\Harvest;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class AdminHarvestChart extends ChartWidget
{
    protected static ?string $heading = 'Harvests per Month';

    protected static ?int $sort = 3;
    protected static string $color = 'success';
    
    protected static bool $isLazy = false;


    protected function getData(): array
{
    $data = Trend::model(Harvest::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->dateColumn('harvest_date')
        ->perMonth()
        ->sum('total_harvest');
 
    return [
        'datasets' => [
            [
                'label' => 'Total Harvest(kg)',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
}

    protected function getType(): string
    {
        return 'line';
    }
}
