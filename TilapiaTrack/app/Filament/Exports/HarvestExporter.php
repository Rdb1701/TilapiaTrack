<?php

namespace App\Filament\Exports;

use App\Models\Harvest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class HarvestExporter extends Exporter
{
    protected static ?string $model = Harvest::class;

    public function collection()
    {

        return Harvest::with(['fingerling.fishpond', 'fingerling.user'])->get();
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('fingerling.fishpond.name')->label('Fishpond'),
            ExportColumn::make('fingerling.fishpond.user.name')->label('Owner'),
            ExportColumn::make('harvest_date')->label('Harvest Date'),
            ExportColumn::make('total_harvest')->label('Total Harvest'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your harvest export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
