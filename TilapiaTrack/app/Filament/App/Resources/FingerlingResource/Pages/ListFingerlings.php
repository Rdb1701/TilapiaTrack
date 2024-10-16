<?php

namespace App\Filament\App\Resources\FingerlingResource\Pages;

use App\Filament\App\Resources\FingerlingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFingerlings extends ListRecords
{
    protected static string $resource = FingerlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
