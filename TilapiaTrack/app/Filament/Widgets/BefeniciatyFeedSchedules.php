<?php

namespace App\Filament\Widgets;

use App\Models\FeedingSchedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BefeniciatyFeedSchedules extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = "full";

    protected static ?string $heading = 'Feeding Schedules';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FeedingSchedule::query()
                    ->select('feeding_schedules.id', 'users.name as owner', 'feeding_schedules.feed_time', 'fingerlings.quantity', 'feeding_schedules.days_of_week', 'fishponds.name as fishpond_name')
                    ->join('fingerlings', 'fingerlings.id', '=', 'feeding_schedules.fingerling_id')
                    ->join('fishponds', 'fishponds.id', 'fingerlings.fishpond_id')
                    ->join('users', 'users.id', 'fishponds.user_id')
            )
            ->columns([
                Tables\Columns\TextColumn::make('owner')
                    ->label('Beneficiary Name'),
                Tables\Columns\TextColumn::make('fishpond_name')
                    ->label('Fishpond'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Fingerling Quantity'),
                Tables\Columns\TextColumn::make('feed_time')
                    ->label('Feed Time')
                    ->getStateUsing(function ($record) {
                        // Directly use the array
                        $times = $record->feed_time;

                        // Format each time and join them with a comma
                        return collect($times)->map(function ($time) {
                            return Carbon::createFromFormat('H:i:s', $time)->format('h:i A');
                        })->implode(', ');
                    }),
                Tables\Columns\TextColumn::make('days_of_week')
                    ->label('Days of Week')
            ]);
    }
}
