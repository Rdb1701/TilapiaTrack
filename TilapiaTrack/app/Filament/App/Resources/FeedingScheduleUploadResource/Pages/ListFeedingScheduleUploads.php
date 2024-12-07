<?php

namespace App\Filament\App\Resources\FeedingScheduleUploadResource\Pages;

use App\Filament\App\Resources\FeedingScheduleUploadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedingScheduleUploads extends ListRecords
{
    protected static string $resource = FeedingScheduleUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
