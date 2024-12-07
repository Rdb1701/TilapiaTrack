<?php

namespace App\Filament\App\Resources\FeedingScheduleUploadResource\Pages;

use App\Filament\App\Resources\FeedingScheduleUploadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedingScheduleUpload extends EditRecord
{
    protected static string $resource = FeedingScheduleUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
