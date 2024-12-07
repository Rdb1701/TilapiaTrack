<?php

namespace App\Filament\Resources\FeedingScheduleUploadResource\Pages;

use App\Filament\Resources\FeedingScheduleUploadResource;
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
