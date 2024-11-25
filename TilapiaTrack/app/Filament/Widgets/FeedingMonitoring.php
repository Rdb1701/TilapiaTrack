<?php

namespace App\Filament\Widgets;

use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FeedingMonitoring extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FeedingSchedule::query()
                    ->whereHas('feedingProgram', function ($query) {
                        $query->where('fish_size', 'Adult');
                    })
                    ->with('feedingProgram')
                    ->with('fingerling')
                    ->with('fingerling.fishpond.user')
            )
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->label('Fishpond'),
                Tables\Columns\TextColumn::make('fingerling.fishpond.user.name')
                    ->label('Owner'),
                Tables\Columns\TextColumn::make('feedingProgram.name')
                    ->label('Feed Program'),
                Tables\Columns\TextColumn::make('feedingProgram.fish_size')
                    ->label('Fish Size'),
                Tables\Columns\TextColumn::make('harvest_countdown')
                    ->label('Harvest Countdown')
                    ->getStateUsing(function ($record) {
                        if (!$record->feedingProgram || !$record->end_date) {
                            return 'Invalid end date';
                        }

                        $endDate = Carbon::parse($record->end_date);
                        $today = Carbon::now();

                        if ($today->greaterThan($endDate)) {
                            return "Your fishes should be harvested.";
                        }

                        $timeLeft = $this->getHumanReadableTimeLeft($endDate);
                        return "$timeLeft until harvested.";
                    }),
                Tables\Columns\ViewColumn::make('progress')
                    ->label('Progress')
                    ->view('filament.tables.columns.progress-bar')
                    ->getStateUsing(function ($record) {
                        if (!$record->feedingProgram || !$record->start_date || !$record->end_date) {
                            return null;
                        }

                        $startDate = Carbon::parse($record->start_date);
                        $endDate = Carbon::parse($record->end_date);
                        $today = Carbon::now();

                        $totalDuration = $startDate->diffInDays($endDate);
                        $elapsed = $startDate->diffInDays($today);

                        $progress = min(100, max(0, ($elapsed / $totalDuration) * 100));

                        return [
                            'progress' => round($progress, 2),
                            'color' => $progress >= 80 ? 'warning' : 'primary',
                        ];
                    }),
            ]);
    }

    private function getHumanReadableTimeLeft($endDate): string
    {
        $now = Carbon::now();
        $diff = $now->diff($endDate);

        $months = $diff->y * 12 + $diff->m;
        $days = $diff->d;

        $parts = [];
        if ($months > 0) {
            $parts[] = $months . ' month' . ($months > 1 ? 's' : '');
        }
        if ($days > 0) {
            $parts[] = $days . ' day' . ($days > 1 ? 's' : '');
        }

        return implode(' and ', $parts) . ' left';
    }
}
