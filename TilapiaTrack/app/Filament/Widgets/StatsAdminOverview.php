<?php

namespace App\Filament\Widgets;

use App\Models\Fingerling;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Fishpond;

class StatsAdminOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Beneficiaries', User::where('role', 'beneficiary')->count())
                ->description('Total number of beneficiaries')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('Fishponds', Fishpond::count())
                ->description('Total number of fishponds')
                ->chart([7, 2, 2, 3, 15, 4, 17])
                ->color('warning')
                ->icon('heroicon-o-clipboard'),

            Stat::make('Fingerlings', Fingerling::count())
                ->description('Total Distributed Fingerlings')
                ->chart([7, 2, 10, 3, 10, 4, 17])
                ->color('danger')
                ->icon('heroicon-o-clipboard-document-list'),
        ];
    }
}
