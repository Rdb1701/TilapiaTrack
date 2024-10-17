<?php

namespace App\Filament\Widgets;

use App\Models\FeedConsumption;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AdminFeedConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Feeds Consumptions per Month';

    protected static ?int $sort = 3;
    protected static string $color = 'warning';
    
    protected static bool $isLazy = false;


    protected function getData(): array
{
    $data = Trend::model(FeedConsumption::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->dateColumn('consumption_date')
        ->perMonth()
        ->sum('quantity');
 
    return [
        'datasets' => [
            [
                'label' => 'Total Feeds Consumptions(kg)',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
}

    protected function getType(): string
    {
        return 'bar';
    }
}
