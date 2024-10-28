<?php

namespace App\Filament\Widgets;

use App\Filament\Exports\BeneficiaryTotalHarvestExporter;
use App\Filament\Exports\HarvestExporter;
use App\Models\Harvest;
use App\Models\FeedConsumption;
use App\Models\Feed;
use App\Models\User;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction as ActionsExportAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class BeneficiaryTotalHarvest extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = "full";
    
    protected static ?string $heading = 'Beneficiary Total Harvests Reports';

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
            ])->headerActions([ 
                ActionsExportAction::make() 
                    ->exporter(BeneficiaryTotalHarvestExporter::class) 
                    ->formats([
                        ExportFormat::Xlsx, 
                    ])
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->label('Export Data'), 
            ]);
    }
}