<?php

namespace App\Filament\App\Resources\FishpondResource\Pages;

use App\Filament\App\Resources\FishpondResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFishponds extends ListRecords
{
    protected static string $resource = FishpondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
