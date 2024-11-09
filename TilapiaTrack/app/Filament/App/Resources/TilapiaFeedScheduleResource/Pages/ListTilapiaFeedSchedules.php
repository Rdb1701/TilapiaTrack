<?php

namespace App\Filament\App\Resources\TilapiaFeedScheduleResource\Pages;

use App\Filament\App\Resources\TilapiaFeedScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTilapiaFeedSchedules extends ListRecords
{
    protected static string $resource = TilapiaFeedScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
