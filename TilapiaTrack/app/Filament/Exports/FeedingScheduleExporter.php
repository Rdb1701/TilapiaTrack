<?php

namespace App\Filament\Exports;

use App\Models\FeedingSchedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FeedingScheduleExporter extends Exporter
{
    protected static ?string $model = FeedingSchedule::class;

    public function collection()
    {

        return FeedingSchedule::with(['fingerling.fishpond', 'fingerling.user'])->get();
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('fingerling.fishpond.name')->label('Fishpond'),
            ExportColumn::make('fingerling.fishpond.user.name')->label('Owner'),
            ExportColumn::make('fingerling.species')->label('Species'),
            ExportColumn::make('fingerling.quantity')->label('Quantity'),
            ExportColumn::make('feed_time')->label('Feed Time'),
            ExportColumn::make('days_of_week')->label('Days of Week'),
            ExportColumn::make('created_at')->label('Created At'),
            ExportColumn::make('updated_at')->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your feeding schedule export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
