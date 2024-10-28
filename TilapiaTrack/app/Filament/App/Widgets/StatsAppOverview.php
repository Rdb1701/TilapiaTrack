<?php

namespace App\Filament\App\Widgets;

use App\Models\FeedingSchedule;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsAppOverview extends BaseWidget
{
    protected static bool $isLazy = false;


    protected function getStats(): array
    {
        return [

            Stat::make('Fishponds', Fishpond::where('user_id', Auth::id())->count())
                ->description('Total number of fishponds')
                ->chart([7, 2, 2, 3, 15, 4, 17])
                ->color('warning')
                ->icon('heroicon-o-clipboard'),

            Stat::make('Fingerlings', Fingerling::join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
                ->where('fishponds.user_id', Auth::id())
                ->count())
                ->description('Total Fingerlings')
                ->chart([7, 2, 10, 3, 10, 4, 17])
                ->color('success')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Feeding Schedules', FeedingSchedule::join('fingerlings', 'feeding_schedules.fingerling_id', '=', 'fingerlings.id')
                ->join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
                ->where('fishponds.user_id', Auth::id())
                ->count())
                ->description('Total Feeding Schedules')
                ->chart([5, 3, 7, 1, 8, 6, 9])
                ->color('info')
                ->icon('heroicon-o-calendar'),


        ];
    }
}
