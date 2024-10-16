<?php

namespace App\Filament\App\Resources\FeedConsumptionResource\Pages;

use App\Filament\App\Resources\FeedConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedConsumption extends EditRecord
{
    protected static string $resource = FeedConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
