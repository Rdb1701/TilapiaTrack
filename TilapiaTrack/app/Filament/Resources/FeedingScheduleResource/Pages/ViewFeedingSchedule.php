<?php

namespace App\Filament\Resources\FeedingScheduleResource\Pages;

use App\Filament\Resources\FeedingScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedingSchedule extends ViewRecord
{
    protected static string $resource = FeedingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
