<?php

namespace App\Filament\Resources\FeedConsumptionResource\Pages;

use App\Filament\Resources\FeedConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedConsumption extends EditRecord
{
    protected static string $resource = FeedConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
