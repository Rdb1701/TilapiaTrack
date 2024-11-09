<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction as ActionsExportAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BeneficiaryTotalHarvestReport extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
        ->query(
            User::query()
                ->select('users.id', 'users.name', DB::raw('SUM(harvests.total_harvest) as total_harvest'), DB::raw('SUM(feed_consumptions.quantity) as total_feed_consumed'), DB::raw('SUM(feed_consumptions.quantity * feeds.price_per_kilo) as total_feed_cost'))
                ->join('fishponds', 'users.id', '=', 'fishponds.user_id')
                ->join('fingerlings', 'fishponds.id', '=', 'fingerlings.fishpond_id')
                ->join('harvests', 'fingerlings.id', '=', 'harvests.fingerling_id')
                ->join('feed_consumptions', 'fingerlings.id', '=', 'feed_consumptions.fingerling_id')
                ->join('feeds', 'feed_consumptions.feed_id', '=', 'feeds.id')
                ->where('users.id', Auth::id())
                ->groupBy('users.id', 'users.name') 
        )
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Beneficiary Name'),
            Tables\Columns\TextColumn::make('total_harvest')
                ->label('Total Harvest (kg)')
                ->formatStateUsing(fn($state) => number_format($state, 2)),
            Tables\Columns\TextColumn::make('total_feed_consumed')
                ->label('Total Feed Consumed (kg)')
                ->formatStateUsing(fn($state) => number_format($state, 2)),
            Tables\Columns\TextColumn::make('total_feed_cost')->label('Total Feed Cost')
                ->formatStateUsing(fn($state) => '₱' . number_format($state, 2)),
        ]);
    }
}
