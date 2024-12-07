<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingScheduleResource\Pages;
use App\Filament\Resources\FeedingScheduleResource\RelationManagers;
use App\Models\FeedingSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\FeedingScheduleExporter;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ExportBulkAction;


class FeedingScheduleResource extends Resource
{
    protected static ?string $model = FeedingSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Fingerling Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('fingerling_id')
                    ->options(function () {
                        return \App\Models\Fingerling::with('fishpond.user')
                            ->get()
                            ->mapWithKeys(function ($fingerling) {
                                return [
                                    $fingerling->id => $fingerling->fishpond->name . ' - ' . $fingerling->fishpond->user->name . ' | ' . $fingerling->species . ' | ' . $fingerling->quantity,
                                ];
                            });
                    })->label('Fishpond | Owner | Species | Quantity')
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
                Tables\Columns\TextColumn::make('fingerling.fishpond.user.name')
                    ->searchable()
                    ->label('Owner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feedingProgram.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed_time')
                    ->label('Feeding Time')
                    ->getStateUsing(function ($record) {
                        // Directly use the array
                        $times = $record->feedingProgram->feed_time;

                        // Format each time and join them with a comma
                        return collect($times)->map(function ($time) {
                            return Carbon::createFromFormat('H:i:s', $time)->format('h:i A');
                        })->implode(', ');
                    }),
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
                // Tables\Columns\TextColumn::make('duration')
                //     ->label('Duration')
                //     ->getStateUsing(function ($record) {
                //         // Ensure start_date and end_date exist in the record
                //         $startDate = Carbon::parse($record->feedingProgram->start_date);
                //         $endDate = Carbon::parse($record->feedingProgram->end_date);


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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('sendNotification')
                        ->icon('heroicon-o-bell')
                        ->form([
                            Forms\Components\Textarea::make('custom_message')
                                ->label('Message')
                                ->placeholder('Type your custom message for the Beneficiary...')
                                ->required(),
                        ])
                        ->action(function (array $data, FeedingSchedule $schedule) {
                            // Retrieve the user related to the feeding schedule via fingerling -> fishpond -> user
                            $user = $schedule->fingerling->fishpond->user;

                            if ($user) {
                                // Use the custom message provided in the form
                                $customMessage = $data['custom_message'];

                                Notification::make()
                                    ->title('Feeding Time Reminder!')
                                    ->body("Hello {$user->name}, {$customMessage}")
                                    ->icon('heroicon-o-information-circle')
                                    ->iconColor('success')
                                    ->broadcast($user)
                                    ->sendToDatabase($user);


                                Notification::make()
                                    ->title('Notification sent successfully')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('User not found')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
                    ->label('More actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('primary')
                    ->button()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(FeedingScheduleExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])
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
            'view' => Pages\ViewFeedingSchedule::route('/{record}'),
            'edit' => Pages\EditFeedingSchedule::route('/{record}/edit'),
        ];
    }
}
