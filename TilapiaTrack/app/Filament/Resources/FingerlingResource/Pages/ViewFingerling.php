<?php

namespace App\Filament\Resources\FingerlingResource\Pages;

use App\Filament\Resources\FingerlingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFingerling extends ViewRecord
{
    protected static string $resource = FingerlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
