<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FeedingScheduleResource\Pages;
use App\Filament\App\Resources\FeedingScheduleResource\RelationManagers;
use App\Models\FeedingSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FeedingScheduleResource extends Resource
{
    protected static ?string $model = FeedingSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Feeding Schedules';

    protected static ?string $navigationLabel = 'Feeding Schedules';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\select::make('fingerling_id')
                        ->options(function () {
                            return \App\Models\Fingerling::with('fishpond.user')
                                ->get()
                                ->mapWithKeys(function ($fingerling) {
                                    return [
                                        $fingerling->id => $fingerling->fishpond->name . ' - ' . $fingerling->fishpond->user->name . ' | ' . $fingerling->species . ' | ' . $fingerling->quantity,
                                    ];
                                });
                        })->label('Fishpond | Owner| Species | Quantity')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TimePicker::make('feed_time')
                        ->required(),
                    Forms\Components\Select::make('days_of_week')
                        ->required()
                        ->multiple()
                        ->options([
                            'Monday'    => 'Monday',
                            'Tuesday'   => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday'  => 'Thursday',
                            'Friday'    => 'Friday',
                            'Saturday'  => 'Saturday',
                            'Sunday'    => 'Sunday',
                        ])
                        ->native(false)
                        ->placeholder('Select days of the week'),
                ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                ->numeric()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('fingerling.species')
                ->numeric()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('fingerling.quantity')
                ->numeric()
                ->label('Quantity')
                ->sortable(),
            Tables\Columns\TextColumn::make('feed_time')
                ->label('Feed Time')
                ->dateTime('h:i A'),
            Tables\Columns\TextColumn::make('days_of_week')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListFeedingSchedules::route('/'),
            'create' => Pages\CreateFeedingSchedule::route('/create'),
            'edit' => Pages\EditFeedingSchedule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('fingerling.fishpond', function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
