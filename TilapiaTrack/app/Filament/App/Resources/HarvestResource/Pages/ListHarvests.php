<?php

namespace App\Filament\App\Resources\HarvestResource\Pages;

use App\Filament\App\Resources\HarvestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHarvests extends ListRecords
{
    protected static string $resource = HarvestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
