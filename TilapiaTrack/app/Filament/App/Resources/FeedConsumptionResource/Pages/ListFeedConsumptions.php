<?php

namespace App\Filament\App\Resources\FeedConsumptionResource\Pages;

use App\Filament\App\Resources\FeedConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedConsumptions extends ListRecords
{
    protected static string $resource = FeedConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
