<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BeneficiaryTotalHarvestReportChart extends ChartWidget
{
    protected static ?string $heading = 'Beneficiary Total Harvest Data';

    protected static ?int $sort = 1;

    protected static string $color = 'success';
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $userId = Auth::id();

        // Query to get total harvest, feed consumed, and feed cost for the authenticated user
        $data = User::query()
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(harvests.total_harvest) as total_harvest'),
                DB::raw('SUM(feed_consumptions.quantity) as total_feed_consumed'),
                DB::raw('SUM(feed_consumptions.quantity * feeds.price_per_kilo) as total_feed_cost')
            )
            ->join('fishponds', 'users.id', '=', 'fishponds.user_id')
            ->join('fingerlings', 'fishponds.id', '=', 'fingerlings.fishpond_id')
            ->join('harvests', 'fingerlings.id', '=', 'harvests.fingerling_id')
            ->join('feed_consumptions', 'fingerlings.id', '=', 'feed_consumptions.fingerling_id')
            ->join('feeds', 'feed_consumptions.feed_id', '=', 'feeds.id')
            ->where('users.id', $userId)
            ->groupBy('users.id', 'users.name')
            ->first(); 

        return [
            'datasets' => [
                [
                    'label' => 'Total Harvest (kg)',
                    'data' => [$data ? $data->total_harvest : 0, 0, 0], 
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 3,
                ],
                [
                    'label' => 'Total Feed Consumed (kg)',
                    'data' => [0, $data ? $data->total_feed_consumed : 0, 0], 
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 3,
                ],
                [
                    'label' => 'Total Feed Cost (₱)',
                    'data' => [0, 0, $data ? $data->total_feed_cost : 0], 
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 3,
                ],
            ],
            'labels' => ['Total Harvest', 'Total Feed Consumed', 'Total Feed Cost'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Change to 'line' or another type as needed
    }
}
