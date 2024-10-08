<?php

namespace App\Filament\Resources\FeedingScheduleResource\Pages;

use App\Filament\Resources\FeedingScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedingSchedule extends EditRecord
{
    protected static string $resource = FeedingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
