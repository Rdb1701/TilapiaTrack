<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingProgramResource\Pages;
use App\Filament\Resources\FeedingProgramResource\RelationManagers;
use App\Models\FeedingProgram;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedingProgramResource extends Resource
{
    protected static ?string $model = FeedingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Feeding Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('feed_id')
                    ->relationship(name: 'feed', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('fish_size')
                    ->options([
                        'Fingerling'  => 'Fingerling',
                        'Juvenile'    => 'Juvenile',
                        'Adult'       => 'Adult',

                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('feed_time')
                    ->options(function () {
                        $times = [];
                        $startTime = Carbon::createFromTime(0, 0);
                        for ($i = 0; $i < 24; $i++) {
                            $times[$startTime->format('H:i')] = $startTime->format('g:i A');
                            $startTime->addHour();
                        }
                        return $times;
                    })
                    ->label('Feed Time')
                    ->multiple()
                    ->native(false)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('duration')
                    ->options([
                        '1'  => '1 Month',
                        '2'  => '2 Months',
                        '3'  => '3 Months',
                        '4'  => '4 Months',
                        '5'  => '5 Months',
                        '6'  => '6 Months',
                        '7'  => '7 Months',
                        '8'  => '8 Months',
                        '9'  => '9 Months',
                        '10' => '10 Months',
                        '11' => '11 Months',
                        '12' => '12 Months',
                    ])
                    ->native(false) // Enables dropdown styling for better user experience
                    ->label('Duration')
                    ->required(),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fish_size')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
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

                Tables\Columns\TextColumn::make('duration')
                    ->sortable()
                    ->getStateUsing(function ($record) {

                        $duration = $record->duration;
                
                        return $duration . ' months';
                    }),

                // Tables\Columns\TextColumn::make('duration')
                //     ->label('Duration')
                //     ->getStateUsing(function ($record) {
                //         $startDate = Carbon::parse($record->start_date);
                //         $endDate = Carbon::parse($record->end_date);

                //         // Calculate the difference between the dates
                //         $diff = $startDate->diff($endDate);

                //         // Format the difference as human-readable duration
                //         if ($diff->y) {
                //             return $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
                //         } elseif ($diff->m) {
                //             return $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
                //         } elseif ($diff->d >= 7) {
                //             return floor($diff->d / 7) . ' week' . (floor($diff->d / 7) > 1 ? 's' : '');
                //         } elseif ($diff->d) {
                //             return $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                //         } elseif ($diff->h) {
                //             return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
                //         } elseif ($diff->i) {
                //             return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
                //         } else {
                //             return $diff->s . ' second' . ($diff->s > 1 ? 's' : '');
                //         }
                //     }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFeedingPrograms::route('/'),
            'create' => Pages\CreateFeedingProgram::route('/create'),
            'edit' => Pages\EditFeedingProgram::route('/{record}/edit'),
        ];
    }
}
