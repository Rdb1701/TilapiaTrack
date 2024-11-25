<?php

namespace App\Filament\Resources\FeedingProgramResource\Pages;

use App\Filament\Resources\FeedingProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedingProgram extends EditRecord
{
    protected static string $resource = FeedingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
