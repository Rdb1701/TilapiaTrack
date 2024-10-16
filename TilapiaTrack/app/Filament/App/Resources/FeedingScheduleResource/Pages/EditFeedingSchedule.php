<?php

namespace App\Filament\App\Resources\FeedingScheduleResource\Pages;

use App\Filament\App\Resources\FeedingScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedingSchedule extends EditRecord
{
    protected static string $resource = FeedingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
