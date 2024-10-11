<?php

namespace App\Filament\Resources\FeedConsumptionResource\Pages;

use App\Filament\Resources\FeedConsumptionResource;
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
