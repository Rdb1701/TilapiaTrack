<?php

namespace App\Filament\Resources\FingerlingResource\Pages;

use App\Filament\Resources\FingerlingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFingerling extends EditRecord
{
    protected static string $resource = FingerlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
