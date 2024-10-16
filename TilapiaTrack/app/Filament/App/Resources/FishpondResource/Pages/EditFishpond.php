<?php

namespace App\Filament\App\Resources\FishpondResource\Pages;

use App\Filament\App\Resources\FishpondResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFishpond extends EditRecord
{
    protected static string $resource = FishpondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
