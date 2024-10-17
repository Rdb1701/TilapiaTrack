<?php

namespace App\Filament\Exports;

use App\Models\Harvest;
use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\DB;

class BeneficiaryTotalHarvestExporter extends Exporter
{
    // Specify the model you're exporting from (optional)
    protected static ?string $model = Harvest::class;

    /**
     * Define the columns to export.
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Beneficiary Name'),
            ExportColumn::make('total_harvest')->label('Total Harvest (kg)'),
            ExportColumn::make('total_feed_consumed')->label('Total Feed Consumed (kg)'),
            ExportColumn::make('total_feed_cost')->label('Total Feed Cost'),
        ];
    }

    /**
     * Define the query used for exporting the data.
     */
    // public function query()
    // {
    //     return User::query()
    //         ->select(
    //             'users.name', 
    //             DB::raw('SUM(harvests.total_harvest) as total_harvest'),
    //             DB::raw('SUM(feed_consumptions.quantity) as total_feed_consumed'),
    //             DB::raw('SUM(feed_consumptions.quantity * feeds.price_per_kilo) as total_feed_cost')
    //         )
    //         ->join('fishponds', 'users.id', '=', 'fishponds.user_id')
    //         ->join('fingerlings', 'fishponds.id', '=', 'fingerlings.fishpond_id')
    //         ->join('harvests', 'fingerlings.id', '=', 'harvests.fingerling_id')
    //         ->join('feed_consumptions', 'fingerlings.id', '=', 'feed_consumptions.fingerling_id')
    //         ->join('feeds', 'feed_consumptions.feed_id', '=', 'feeds.id')
    //         ->groupBy('users.id', 'users.name');
    // }

    /**
     * Customize the notification message after export completion.
     */
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your beneficiary total harvest export has completed and ' 
                . number_format($export->successful_rows) . ' ' 
                . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' 
                     . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
