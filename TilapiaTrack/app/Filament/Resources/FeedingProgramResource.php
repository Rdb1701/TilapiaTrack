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
                Forms\Components\TextInput::make('age_range')
                    ->numeric()
                    ->placeholder('in weeks')
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
                        'Sub-Adult'       => 'Sub-Adult',
                        'Adult'       => 'Adult',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('feeding_frequency')
                    ->numeric()
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
                Forms\Components\TextInput::make('fish_amount')
                    ->label('Feed Amount')
                    ->required()
                    ->placeholder('% OF BODY WEIGHT')
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('protein_content')
                    ->required()
                    ->placeholder('in %')
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('typical_weight_range')
                    ->required()
                    ->placeholder('in grams')
                    ->maxLength(255),
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
                    ->label('Fish Growth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('age_range')
                    ->label('Age Duration')
                    ->formatStateUsing(fn($state) => $state . ' weeks')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fish_amount')
                    ->label('Feed Amount')
                    ->formatStateUsing(fn($state) => $state . ' %')
                    ->searchable(),
                Tables\Columns\TextColumn::make('protein_content')
                    ->label('Protein Content')
                    ->formatStateUsing(fn($state) => $state . ' %')
                    ->searchable(),
                Tables\Columns\TextColumn::make('feed.name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('feed_time')
                //     ->label('Feed Time')
                //     ->getStateUsing(function ($record) {
                //         // Directly use the array
                //         $times = $record->feed_time;

                //         // Format each time and join them with a comma
                //         return collect($times)->map(function ($time) {
                //             return Carbon::createFromFormat('H:i:s', $time)->format('h:i A');
                //         })->implode(', ');
                //     }),
                Tables\Columns\TextColumn::make('typical_weight_range')
                ->formatStateUsing(fn($state) => $state . ' grams')
                ->searchable(),


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
