<?php

namespace App\Filament\Resources;

use App\Filament\Exports\HarvestExporter;
use App\Filament\Resources\HarvestResource\Pages;
use App\Filament\Resources\HarvestResource\RelationManagers;
use App\Models\Harvest;
use App\Models\User;
use Filament\Tables\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HarvestResource extends Resource
{
    protected static ?string $model = Harvest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Fingerling Consumptions';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('fingerling_id')
                    ->options(function () {
                        return \App\Models\Fingerling::whereHas('fishpond.user')
                            ->with('fishpond.user')
                            ->get()
                            ->mapWithKeys(function ($fingerling) {
                                return [
                                    $fingerling->id => $fingerling->fishpond->name . ' - '. $fingerling->fishpond->user->name . ' | ' . $fingerling->species . ' | ' . $fingerling->quantity,
                                ];
                            });
                    })->label('Fishpond | Owner| Species | Quantity'),
                Forms\Components\DatePicker::make('harvest_date')
                    ->required(),
                Forms\Components\TextInput::make('total_harvest')
                    ->placeholder('Kilograms')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Harvest Picture')
                    ->required()
                    ->imageResizeMode('cover')
                    ->openable()
                    ->multiple()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fingerling.fishpond.user.name')
                    ->numeric()
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_harvest')
                    ->formatStateUsing(fn($state) => $state . ' kg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harvest_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Fishpond Pictures')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText(),
            ])
            ->filters([
                SelectFilter::make('owner')
                    ->label('Filter by Owner')
                    ->options(User::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $userId): Builder => $query->whereHas('fingerling.fishpond.user', function (Builder $query) use ($userId) {
                                $query->where('id', $userId);
                            })
                        );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])->headerActions([ 
                ExportAction::make() 
                    ->exporter(HarvestExporter::class) 
                    ->formats([
                        ExportFormat::Xlsx, 
                    ])
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->label('Export Data'), 
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHarvests::route('/'),
            'create' => Pages\CreateHarvest::route('/create'),
            'edit' => Pages\EditHarvest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}