<?php

namespace App\Filament\Resources\FishpondResource\Pages;

use App\Filament\Resources\FishpondResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFishpond extends ViewRecord
{
    protected static string $resource = FishpondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
