<?php

namespace App\Filament\Exports;

use App\Models\FeedConsumption;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FeedConsumptionExporter extends Exporter
{
    protected static ?string $model = FeedConsumption::class;

    public function collection()
    {

        return FeedConsumption::with(['fingerling.fishpond', 'fingerling.user'])->get();
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('fingerling.fishpond.name')->label('Fishpond'),
            ExportColumn::make('feed.name'),
            ExportColumn::make('quantity'),
            ExportColumn::make('consumption_date'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your feed consumption export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
