<?php

namespace App\Filament\Resources\FeedConsumptionResource\Pages;

use App\Filament\Resources\FeedConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedConsumption extends ViewRecord
{
    protected static string $resource = FeedConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
