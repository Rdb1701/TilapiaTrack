<?php

namespace App\Filament\Resources\TilapiaFeedScheduleResource\Pages;

use App\Filament\Resources\TilapiaFeedScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTilapiaFeedSchedule extends ViewRecord
{
    protected static string $resource = TilapiaFeedScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
