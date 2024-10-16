<?php

namespace App\Filament\App\Resources\FingerlingResource\Pages;

use App\Filament\App\Resources\FingerlingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFingerling extends EditRecord
{
    protected static string $resource = FingerlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
