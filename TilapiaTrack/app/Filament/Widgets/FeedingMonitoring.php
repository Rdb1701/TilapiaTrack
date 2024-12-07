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
                        if (!$record || !$record->end_date) {
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
                        if (!$record || !$record->start_date || !$record->end_date) {
                            return null;
                        }

                        $startDate = Carbon::parse($record->start_date);
                        $endDate = Carbon::parse($record->end_date);
                        $today = Carbon::now();

                        // Calculate total duration in days
                        $totalDuration = $startDate->diffInDays($endDate);

                        // Handle case where start and end dates are the same (avoid division by zero)
                        if ($totalDuration <= 0) {
                            // If total duration is zero or negative (same start and end date), return 100% progress
                            return [
                                'progress' => 100,
                                'color' => 'primary',
                            ];
                        }

                        // Calculate the elapsed time in days
                        $elapsed = $startDate->diffInDays($today);

                        // Calculate the progress as a percentage of elapsed time vs total duration
                        $progress = min(100, max(0, ($elapsed / $totalDuration) * 100));

                        // If it's the day before harvest, force progress to 100%
                        if ($today->isAfter($endDate->subDay())) {
                            $progress = 100;
                        }

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
