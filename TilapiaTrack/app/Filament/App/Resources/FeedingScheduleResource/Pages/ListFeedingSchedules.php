<?php

namespace App\Filament\App\Resources\FeedingScheduleResource\Pages;

use App\Filament\App\Resources\FeedingScheduleResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListFeedingSchedules extends ListRecords
{
    protected static string $resource = FeedingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            // 'All' => Tab::make('All')
            //     ->query(fn($query) => $query),
            'Fingerling' => Tab::make('Fingerling')
                ->query(fn($query) => $query->whereHas('feedingProgram', fn($q) => $q->where('fish_size', 'Fingerling'))),
            'Juvenile' => Tab::make('Juvenile')
                ->query(fn($query) => $query->whereHas('feedingProgram', fn($q) => $q->where('fish_size', 'Juvenile'))),
            'Adult' => Tab::make('Adult')
                ->query(fn($query) => $query->whereHas('feedingProgram', fn($q) => $q->where('fish_size', 'Adult'))),
        ];
    }
}
