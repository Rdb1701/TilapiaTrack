<?php

namespace App\Filament\Resources\TilapiaFeedScheduleResource\Pages;

use App\Filament\Resources\TilapiaFeedScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTilapiaFeedSchedule extends EditRecord
{
    protected static string $resource = TilapiaFeedScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
