<?php

namespace App\Filament\Resources\FeedingProgramResource\Pages;

use App\Filament\Resources\FeedingProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedingPrograms extends ListRecords
{
    protected static string $resource = FeedingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
