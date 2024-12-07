<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FeedingScheduleResource\Pages;
use App\Filament\App\Resources\FeedingScheduleResource\RelationManagers;
use App\Models\FeedingSchedule;
use Carbon\Carbon;
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

    protected static ?string $navigationGroup = 'Feeding Management';

    protected static ?string $navigationLabel = 'Feeding Schedules';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('fingerling_id')
                    ->options(function () {
                        return \App\Models\Fingerling::whereHas('fishpond.user', function ($query) {
                            $query->where('users.id', Auth::id());  // Assuming the user table is named 'users'
                        })
                            ->with('fishpond.user')  // Eager load the fishpond and user relationships
                            ->get()
                            ->mapWithKeys(function ($fingerling) {
                                return [
                                    $fingerling->id => $fingerling->fishpond->name . ' - ' . $fingerling->fishpond->user->name . ' | ' . $fingerling->species . ' | ' . number_format($fingerling->quantity),
                                ];
                            });
                    })
                    ->label('Fishpond | Owner | Species | Quantity')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('feeding_program_id')
                    ->options(function () {
                        return \App\Models\FeedingProgram::with('feed')
                            ->get()
                            ->mapWithKeys(function ($program) {
                                // Return the feeding program info with duration included
                                return [
                                    $program->id => $program->name . ' - ' . $program->fish_size . ' | ' . $program->feed->name . ' | Duration: ' . $program->duration . " months",
                                ];
                            });
                    })->label('Feeding Program | Fish Size | Feeds | Duration')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Fetch the selected program's duration and calculate end date
                        $program = \App\Models\FeedingProgram::find($state);

                        if ($program) {
                            $startDate = Carbon::today();

                            $set('start_date', $startDate->toDateString());
                            $set('end_date', $startDate->addMonths(intval($program->duration))->toDateString());
                        }
                    }),

                Forms\Components\DatePicker::make('start_date')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fingerling.quantity')
                    ->numeric()
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feedingProgram.fish_size')
                    ->searchable()
                    ->label('Fish Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feedingProgram.duration')
                    ->label('Duration')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {

                        $duration = $record->feedingProgram->duration;

                        return $duration . ' months';
                    }),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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

    // public static function canCreate(): bool
    // {
    //     return false;
    // }
}
