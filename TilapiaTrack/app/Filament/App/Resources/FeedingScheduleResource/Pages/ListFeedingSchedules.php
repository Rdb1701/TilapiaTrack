<?php

namespace App\Filament\App\Resources\FeedingScheduleResource\Pages;

use App\Filament\App\Resources\FeedingScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedingSchedules extends ListRecords
{
    protected static string $resource = FeedingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
